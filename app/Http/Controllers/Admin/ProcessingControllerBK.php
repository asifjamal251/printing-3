<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Processing\ProcessingCollection;
use App\Models\Dye;
use App\Models\Item;
use App\Models\ItemProcessDetail;
use App\Models\JobCard;
use App\Models\JobCardItem;
use App\Models\OrderSheet;
use App\Models\Processing;
use App\Models\ProcessingItem;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcessingController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $datas = ProcessingItem::orderByRaw("CASE
                        WHEN status_id = 1 THEN 1
                        WHEN status_id = 3 THEN 2
                        ELSE 3
                    END")
                    ->orderBy('created_at', 'desc');
            $totaldata = $datas->count();

            $status = $request->input('status');
            if ($status) {
                $datas->where('status_id', $status);
            }
            
            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new ProcessingCollection($datas));
        }
        return view('admin.processing.list');
    }


    public function updateUps(Request $request){ 
        if($request->ups){
            ProcessingItem::where('id', $request->id)->update(['ups' => $request->ups]);

            $processing = ProcessingItem::where('id', $request->id)->first();
            ItemProcessDetail::where('purchase_order_item_id', $processing->purchase_order_item_id)->update(['ups' => $request->ups]);
            
            return response()->json([
                'message' => 'UPS Updated successfully.',
                'title' => 'Processing Updated.',
                'class' => 'bg-success'
            ]);
        }
        return response()->json([
            'message' => 'Something went wrong.',
            'title' => 'Processing.',
            'class' => 'bg-danger'
        ]);
    }



    public function updateDesigner(Request $request){ 
        if($request->designer){
            $processingItems = ProcessingItem::where('id', $request->id)->first();
            Processing::where('id', $processingItems->processing_id)->update(['designer' => $request->designer]);
            
            return response()->json([
                'message' => 'Designer Updated successfully.',
                'title' => 'Processing Updated.',
                'class' => 'bg-success'
            ]);
        }
        return response()->json([
            'message' => 'Something went wrong.',
            'title' => 'Processing.',
            'class' => 'bg-danger'
        ]);
    }


    public function store(Request $request){
        $processing = ProcessingItem::where('id', $request->id)->firstOrFail();
        $item = Item::findOrFail($processing->item_id);

        $processing_session = session()->get('processing', []);

        if (isset($processing_session[$request->id])) {
            unset($processing_session[$request->id]);
            session()->put('processing', $processing_session);
            return response()->json([
                'message' => 'Removed from processing successfully',
                'class' => 'bg-warning',
                'added' => false
            ]);
        }

        if (!empty($processing_session)) {
            $existingIds = array_column($processing_session, 'processing_id');
            $existingItems = ProcessingItem::with('purchaseOrderItem.purchaseOrder')
            ->whereIn('id', $existingIds)
            ->get();

            $existingJobType = $existingItems->first()->job_type ?? null;
            if(empty($processing->job_type)){
                return response()->json([
                        'message' => 'Job Type Required.',
                        'class' => 'bg-danger',
                        'added' => false
                    ]);
            }

            foreach ($existingItems as $existing) {
                $poItem = $existing->purchaseOrderItem;
                $po = $poItem->purchaseOrder;
                if ($existingJobType !== $processing->job_type) {
                    return response()->json([
                        'message' => 'All selected items must have the same Job Type.',
                        'class' => 'bg-danger',
                        'added' => false
                    ]);
                }
                if (
                    $po->gsm != $processing->purchaseOrderItem->purchaseOrder->gsm ||
                    $po->other_coating != $processing->purchaseOrderItem->purchaseOrder->other_coating ||
                    $po->product_type_id != $processing->purchaseOrderItem->purchaseOrder->product_type_id ||
                    $po->embossing != $processing->purchaseOrderItem->purchaseOrder->embossing ||
                    $po->leafing != $processing->purchaseOrderItem->purchaseOrder->leafing ||
                    $po->coating != $processing->purchaseOrderItem->purchaseOrder->coating
                ) {
                    return response()->json([
                        'message' => 'All selected items must have the same coating type, paper type, and GSM.',
                        'class' => 'bg-danger',
                        'added' => false
                    ]);
                }
            }
        }

        if($processing->ups == null){
            return response()->json([
                'message' => 'UPS is requred',
                'class' => 'bg-warning',
                'added' => false
            ]);
        }

        $processing_session[$request->id] = [
            "processing_id" => $processing->id,
            "user_id" => auth('admin')->id(),
            "job_type" => $processing->job_type,
        ];

        session()->put('processing', $processing_session);

        return response()->json([
            'message' => 'Added to processing successfully',
            'class' => 'bg-success',
            'added' => true
        ]);
    }


    public function create(Request $request){
        $processingItems = session('processing', []);

        $cartonSizes = collect();

        foreach ($processingItems as $item) {
            $processingItem = ProcessingItem::with('item')
                ->find($item['processing_id']);

            if ($processingItem && $processingItem->item && !empty($processingItem->item->item_size)) {
                $cartonSizes->push($processingItem->item->item_size);
            }
        }

        $cartonSizes = $cartonSizes->unique();
        
        $dye = null;

        if ($cartonSizes->count() === 1) {
            $cartonSize = $cartonSizes->first();

            $dye = Dye::whereHas('dyeDetails', function ($q) use ($cartonSize) {
                $q->whereRaw(
                    "CONCAT_WS('*', length, width, height) = ?",
                    [$cartonSize]
                );
            })->with('dyeDetails')->first();
        }
        //dd($dye);

        return view('admin.processing.create', compact('dye'));
    }
    


    public function storeJobCard(Request $request)
    {
        

        if (empty(session('processing', []))) {
            return response()->json([
                'class'         => 'bg-danger',
                'error'         => true,
                'message'       => 'Select at least one item to create a Job Card.',
                'table_refresh' => true,
                'call_back'     => '',
                'model_id'      => 'dataSave',
            ]);
        }

        $request->validate([
            'dye'         => 'nullable|exists:dyes,id',
            'sheet_size'  => 'required|string|max:255',
            'set_number'  => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $hasEmbossing = false;
                $hasLeafing   = false;

                $coatingCategory    = null;
                $otherCoatingCategory = null;
                $otherCoatingGroup = null;
        
                $processingItems = session('processing', []);
                $isUrgent = collect($processingItems)->contains('urgent', 'Yes') ? 'Yes' : 'No';
                $jobType = collect($processingItems)->contains('job_type', 'Separate') ? 'Separate' : 'Mix';

                $jobCard = JobCard::create([
                    'dye_id'         => $request->dye ?? null,
                    'sheet_size'     => $request->sheet_size,
                    'set_number'     => $request->set_number,
                    'job_type'       => $jobType,
                    'urgent'         => $isUrgent,
                ]);

                foreach ($processingItems as $item) {
                    $processing_item = ProcessingItem::where('id', $item['processing_id'])->where('status_id', 1)->firstOrFail();

                    if ($processing_item->itemProcessDetail->embossing === 'Yes') {
                        $hasEmbossing = true;
                    }

                    if ($processing_item->itemProcessDetail->leafing === 'Yes') {
                        $hasLeafing = true;
                    }

                    // ---------- COATING CATEGORY ----------
                    $currentCoatingCategory =
                        $processing_item->itemProcessDetail->coatingType?->category ?? 'None';

                    if ($coatingCategory === null) {
                        $coatingCategory = $currentCoatingCategory;
                    } elseif ($coatingCategory !== $currentCoatingCategory) {
                        throw new \Exception('All items must have same coating category');
                    }

                    // ---------- OTHER COATING CATEGORY ----------


                    $getOtherCoatingGroup = function (?string $category): string {
                        if (empty($category)) {
                            return 'None';
                        }

                        $value = strtolower(trim($category));

                        if (str_contains($value, 'metallic')) {
                            return 'Metallic';
                        }

                        if ($value === 'spot uv') {
                            return 'Spot UV';
                        }

                        return 'None';
                    };

                    $currentOtherCoatingCategory =
                        $processing_item->itemProcessDetail->otherCoatingType?->category ?? 'None';

                    $currentOtherCoatingGroup = $getOtherCoatingGroup($currentOtherCoatingCategory);

                    if ($otherCoatingGroup === null) {
                        $otherCoatingGroup     = $currentOtherCoatingGroup;
                        $otherCoatingCategory  = $currentOtherCoatingCategory;

                    } elseif ($otherCoatingGroup !== $currentOtherCoatingGroup) {
                        throw new \Exception('All items must have same other coating category');

                    } else {
                        if (
                            stripos($currentOtherCoatingCategory, 'spot uv') !== false &&
                            stripos($currentOtherCoatingCategory, 'metallic') !== false
                        ) {
                            $otherCoatingCategory = 'Spot UV + Metallic';
                        }
                    }


                    if($processing_item){
                        JobCardItem::create(
                            [
                                'job_card_id'             => $jobCard->id,
                                'item_id'                 => $processing_item->item_id, 
                                'purchase_order_item_id'  => $processing_item->purchase_order_item_id,
                                'purchase_order_id'       => $processing_item->purchase_order_id,
                                'item_process_details_id' => $processing_item->item_process_details_id,
                                'ups'                     => $processing_item->itemProcessDetail?->ups,
                                'quantity'                => $processing_item->itemProcessDetail?->quantity,
                                'item_name'               => $processing_item->itemProcessDetail?->item?->item_name,
                                'item_size'               => $processing_item->itemProcessDetail?->item?->item_size,
                                'colour'                  => $processing_item->itemProcessDetail?->colour,
                                'gsm'                     => $processing_item->itemProcessDetail?->gsm,
                                'coating_type_id'              => $processing_item->itemProcessDetail?->coating_type_id,
                                'other_coating_type_id'        => $processing_item->itemProcessDetail?->other_coating_type_id,
                                'embossing'               => $processing_item->itemProcessDetail?->embossing,
                                'leafing'                 => $processing_item->itemProcessDetail?->leafing,
                                'back_print'              => $processing_item->itemProcessDetail?->back_print,
                                'braille'                 => $processing_item->itemProcessDetail?->braille,
                            ]);

                        //dd($processing_item->itemProcessDetail->quantity);

                        if (!empty($processing_item->item_process_details_id)) {
                            ItemProcessDetail::where('id', $processing_item->item_process_details_id)
                            ->update([
                                'job_card_id' => $jobCard->id,
                                'dye_id'      => $request->dye ?? null,
                                'sheet_size'     => $request->sheet_size,
                                'set_number'     => $request->set_number,
                            ]);
                        }

                        $processing_item->status_id   = 3;
                        $processing_item->job_card_id = $jobCard->id;
                        $processing_item->set_number  = $request->set_number;
                        $processing_item->save();
                    }
                }

                $jobCard->attachements()->sync($request->file);

                $totalQty = $jobCard->items->sum('quantity');
                $totalUps = $jobCard->items->sum('ups');


                $jobCard->required_sheet = ($totalUps > 0) ? (int) ceil($totalQty / $totalUps) : 0;
                $jobCard->embossing = $hasEmbossing ? 'Yes' : 'No';
                $jobCard->leafing = $hasLeafing ? 'Yes' : 'No';
                $jobCard->coating_type = $coatingCategory;
                $jobCard->other_coating_type = $otherCoatingCategory;
                $jobCard->save();

                $purchaseOrderItemIds = JobCardItem::where('job_card_id', $jobCard->id)->pluck('purchase_order_item_id');

                if ($purchaseOrderItemIds->isNotEmpty()) {
                    PurchaseOrderItem::whereIn('id', $purchaseOrderItemIds)->update(['status_id' => 24]);
                }

                session()->forget('processing');
            });

            return response()->json([
                'class'          => 'bg-success',
                'error'          => false,
                'message'        => 'Job Card created successfully',
                'table_refresh'  => true,
                'call_back'      => '',
                'model_id'       => 'dataSave',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create job card: ' . $e->getMessage(),
                'class'   => 'error',
            ], 500);
        }
    }

    public function backToOrderSheet(Request $request){
        $processingItem = ProcessingItem::find($request->id);

        if (!$processingItem) {
            return response()->json([
                'message' => 'Processing item not found.',
                'title'   => 'Processing',
                'class'   => 'bg-danger'
            ]);
        }

        OrderSheet::where('item_id', $processingItem->item_id)
            ->where('item_process_details_id', $processingItem->item_process_details_id)
            ->update(['status_id' => 1]);

        $processingItem->delete();

        return response()->json([
            'message' => 'Back To Order Sheet Successfully.',
            'title'   => 'Processing Updated.',
            'class'   => 'bg-success'
        ]);
    }

}

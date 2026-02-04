<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\JobCard\JobCardCollection;
use App\Models\Cylinder;
use App\Models\Dye;
use App\Models\JobCard;
use App\Models\JobCardItem;
use App\Models\JobCardProduct;
use App\Models\JobCardStage;
use App\Models\Operator;
use App\Models\PaperCutting;
use App\Models\Printing;
use App\Models\Processing;
use App\Models\ProcessingItem;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductLedger;
use App\Models\PurchaseOrderItem;
use App\Models\Stock;
use App\Services\JobCardWorkflowService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobCardController extends Controller
{
 public function index(Request $request){
    if ($request->ajax()) {
        $query = JobCard::with(['items']);

        $datas = $query->orderByRaw("CASE
            WHEN status_id = 1 THEN 1
            WHEN status_id = 2 THEN 2
            WHEN status_id = 3 THEN 8
            WHEN status_id = 5 THEN 9
            ELSE 3
            END")
        ->orderBy('created_at', 'desc');

        if ($request->filled('set_no')) {
            $datas->where('set_number', 'LIKE', '%' . $request->set_no . '%');
        }

        if ($request->filled('status')) {
            $datas->where('status_id', $request->status);
        }

        if ($request->filled('operator')) {
            $datas->whereHas('printing', function ($q) use ($request) {
                $q->where('operator_id', $request->operator);
            });
        }

        if (
            $request->filled('mkdt_by') ||
            $request->filled('mfg_by') ||
            $request->filled('item_name') ||
            $request->filled('item_size')
        ) {
            $datas->whereHas('items', function ($q) use ($request) {

                if ($request->filled('mkdt_by')) {
                    $q->where('mkdt_by', $request->mkdt_by);
                }

                if ($request->filled('mfg_by')) {
                    $q->where('mfg_by', $request->mfg_by);
                }

                if ($request->filled('item_name')) {
                    $q->where('item_name', 'LIKE', '%' . $request->item_name . '%');
                }

                if ($request->filled('item_size')) {
                    $q->where('item_size', 'LIKE', '%' . $request->item_size . '%');
                }
            });
        }

        $totaldata = $datas->count();
        $request->merge(['recordsTotal' => $totaldata, 'length' => $request->length]);

        $datas = $datas->limit($request->length)->offset($request->start)->get();

        return response()->json(new JobCardCollection($datas));
    }
    return view('admin.job-card.list');
}


public function addDetails($id)
{
    $job_card = JobCard::with(['items.item', 'items.itemProcessDetail'])->findOrFail($id);
    $outerGroups = $job_card->items->groupBy(function ($item) use ($job_card) {
        return $job_card->job_type.'|'.$job_card->job_card_number.'|'.$job_card->set_number;
    });

    return view('admin.job-card.add-details', compact('job_card', 'outerGroups'));
}


public function show(Request $request, $id){
    $job_card = JobCard::with(['items.item', 'items.itemProcessDetail'])->findOrFail($id);
    return view('admin.job-card.view', compact('job_card'));
}



public function update(Request $request, $id)
{
    $request->validate([
        'kt_docs_repeater_advanced' => 'required|array|min:1',
        'kt_docs_repeater_advanced.*.product' => 'required|integer',
        'kt_docs_repeater_advanced.*.item_per_packet' => 'required|integer',
        'kt_docs_repeater_advanced.*.required_sheet' => 'required|numeric|min:0',
        'kt_docs_repeater_advanced.*.wastage' => 'required|numeric|min:0',
        'kt_docs_repeater_advanced.*.paper_devide' => 'required|numeric|min:1',
        'kt_docs_repeater_advanced.*.total_sheet' => 'required|numeric|min:0',
    ],[
        'kt_docs_repeater_advanced.required' => 'At least one product row is required.',
        'kt_docs_repeater_advanced.*.product.required' => 'Product is required.',
        'kt_docs_repeater_advanced.*.item_per_packet.required' => 'Item per packet is required.',
        'kt_docs_repeater_advanced.*.required_sheet.required' => 'Required sheet is required.',
        'kt_docs_repeater_advanced.*.required_sheet.min' => 'Required sheet must be at least 1.',
        'kt_docs_repeater_advanced.*.wastage.required' => 'Wastage value is required.',
        'kt_docs_repeater_advanced.*.paper_devide.required' => 'Paper divide is required.',
        'kt_docs_repeater_advanced.*.paper_devide.min' => 'Paper divide must be at least 1.',
        'kt_docs_repeater_advanced.*.total_sheet.required' => 'Total sheet is required.',
        'kt_docs_repeater_advanced.*.total_sheet.min' => 'Total sheet must be at least 1.',
    ]);


    try {
        DB::transaction(function () use ($request, $id) {

            $jobCard = JobCard::findOrFail($id);

            if (!empty($jobCard->dye_id) && $jobCard->sheet_size === '0X0') {
                $dye = Dye::find($jobCard->dye_id);

                if ($dye && !empty($dye->sheet_size)) {
                    $jobCard->sheet_size = $dye->sheet_size;
                    $jobCard->save();
                }
            }

            $submittedRows = collect($request->kt_docs_repeater_advanced);

            $existingProducts = JobCardProduct::where('job_card_id', $jobCard->id)->get();

            // Handle removed products
            foreach ($existingProducts as $old) {

                $existsInSubmission = $submittedRows->contains(function ($row) use ($old) {
                    return $row['product'] == $old->product_id 
                        && $row['item_per_packet'] == $old->product_attribute_id;
                });

                if (!$existsInSubmission) {

                    $productId = $old->product_id;
                    $attributeId = $old->product_attribute_id;

                    $usedStock = $old->total_sheet??0;

                    $stock = Stock::firstOrCreate(
                        ['product_id' => $productId, 'product_attribute_id' => $attributeId],
                        ['quantity' => 0, 'in_hand_quantity' => 0]
                    );

                    ProductLedger::create([
                        'product_id' => $productId,
                        'product_attribute_id' => $attributeId,
                        'reference_no' => $jobCard->set_number,
                        'type' => 'in',
                        'old_quantity' => $stock->quantity,
                        'new_quantity' => $usedStock,
                        'current_quantity' => $stock->quantity + $usedStock,
                        'source_type' => 'JobCard',
                        'source_id' => $jobCard->id,
                        'note' => 'Stock returned due to product removal',
                        'created_by' => auth('admin')->id(),
                    ]);

                    $stock->quantity += $usedStock;
                    $stock->save();

                    $old->delete();
                }
            }

            // Handle updated or new products
            foreach ($submittedRows as $row) {

                $productId = $row['product'];
                $attributeId = $row['item_per_packet'];

                $oldRow = JobCardProduct::where([
                    'job_card_id' => $jobCard->id,
                    'product_id' => $productId,
                    'product_attribute_id' => $attributeId
                ])->first();

                $oldUsed = $oldRow ? ($oldRow->total_sheet) : 0;
                $newUsed = $row['total_sheet'];
                $difference = $newUsed - $oldUsed;

                $updated = JobCardProduct::updateOrCreate(
                    [
                        'product_id' => $productId,
                        'product_attribute_id' => $attributeId,
                        'job_card_id' => $jobCard->id,
                    ],
                    [
                        'required_sheet' => $row['required_sheet'],
                        'wastage_sheet' => $row['wastage'],
                        'paper_divide' => $row['paper_devide'],
                        'total_sheet' => $row['total_sheet'],
                    ]
                );

                if ($difference != 0) {

                    $stock = Stock::firstOrCreate(
                        ['product_id' => $productId, 'product_attribute_id' => $attributeId],
                        ['quantity' => 0, 'in_hand_quantity' => 0]
                    );

                    ProductLedger::create([
                        'product_id' => $productId,
                        'product_attribute_id' => $attributeId,
                        'reference_no' => $jobCard->set_number,
                        'type' => $difference > 0 ? 'out' : 'in',
                        'old_quantity' => $stock->quantity,
                        'new_quantity' => abs($difference),
                        'current_quantity' => $difference > 0 ? $stock->quantity - $difference : $stock->quantity + abs($difference),
                        'source_type' => 'JobCard',
                        'source_id' => $jobCard->id,
                        'note' => $difference > 0 ? 'Additional stock issued to job card' : 'Stock returned due to job card update',
                        'created_by' => auth('admin')->id(),
                    ]);

                    $stock->quantity = $difference > 0 ? $stock->quantity - $difference : $stock->quantity + abs($difference);
                    $stock->save();
                }
            }

            $jobCard->required_sheet = $jobCard->jobCardProducts->sum('required_sheet');
            $jobCard->wastage_sheet = $jobCard->jobCardProducts->sum('wastage_sheet');
            $jobCard->total_sheet = $jobCard->jobCardProducts->sum('total_sheet');
            $jobCard->remarks = $request->remarks;
            $jobCard->printing = $request->printing;
            $jobCard->tentative_date = Carbon::parse($request->tentative_date)->format('Y-m-d');
            $jobCard->status_id = 23;
            $jobCard->save();


            $attributes = [
                'coating'    => $jobCard->coating_type, 
                'other_coating' => $jobCard->other_coating_type,   
                'embossing'     => $jobCard->embossing,      
                'leafing'     => $jobCard->leafing,      
                'printing'     => $jobCard->printing,      
            ];

           // dd($attributes);
            $stages = JobCardWorkflowService::determineWorkflow($attributes);

            if (empty($stages)) {
                DB::rollBack();
                return response()->json([
                    'class' => 'bg-danger',
                    'error' => true,
                    'message' => 'Module Condition Not Match',
                    'call_back' => '',
                    'table_referesh' => true,
                    'model_id' => ''
                ]);
            }

  

            $normalizedStages = collect($stages)->map(function ($stage) {
                return [
                    'label' => $stage,
                    'name'  => str_replace(' ', '', $stage), // IMPORTANT for model mapping
                ];
            });

            $existingStages = JobCardStage::where('job_card_id', $jobCard->id)
                ->get()
                ->keyBy('order');

            foreach ($normalizedStages as $index => $stage) {

                $order = $index + 1;

                JobCardStage::updateOrCreate(
                    [
                        'job_card_id' => $jobCard->id,
                        'order'       => $order,
                    ],
                    [
                        'name'      => $stage['name'],
                        'label'     => $stage['label'],
                        'status_id' => 1,
                    ]
                );

                unset($existingStages[$order]);
            }


            foreach ($existingStages as $stage) {
                $stage->delete();
            }


        });

        return response()->json([
            'class'          => 'bg-success',
            'error'          => false,
            'message'        => 'Details Updated Successfully',
            'table_refresh'  => true,
            'call_back' => '',
            'model_id'       => 'dataSave',
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
            'title' => 'Order Sheet Error',
            'error' => true,
            'class' => 'bg-danger',
            'call_back' => ''
        ], 500);
    }
}


public function destroy($id)
{
    DB::beginTransaction();

    try {

        $jobCard = JobCard::whereIn('status_id', [1, 2, 23])
            ->with(['jobCardProducts', 'items'])
            ->findOrFail($id);

            if (!$jobCard) {
                throw new \Exception('Cancellation not allowed.');
            }

        if ($jobCard->status_id == 5) {
            throw new \Exception('Job Card already cancelled.');
        }

        foreach ($jobCard->jobCardProducts as $product) {

            $usedStock = $product->total_sheet ?? 0;

            if ($usedStock <= 0) {
                continue;
            }

            $stock = Stock::firstOrCreate(
                [
                    'product_id' => $product->product_id,
                    'product_attribute_id' => $product->product_attribute_id,
                ],
                [
                    'quantity' => 0,
                    'in_hand_quantity' => 0,
                ]
            );

            ProductLedger::create([
                'product_id'           => $product->product_id,
                'product_attribute_id' => $product->product_attribute_id,
                'reference_no'         => $jobCard->set_number,
                'type'                 => 'in',
                'old_quantity'         => $stock->quantity,
                'new_quantity'         => $usedStock,
                'current_quantity'     => $stock->quantity + $usedStock,
                'source_type'          => 'JobCard',
                'source_id'            => $jobCard->id,
                'note'                 => 'Stock reverted due to Job Card cancellation',
                'created_by'           => auth('admin')->id(),
            ]);

            $stock->quantity += $usedStock;
            $stock->save();
        }

        $poItemIds = $jobCard->items
            ->pluck('purchase_order_item_id')
            ->filter()
            ->unique();

        if ($poItemIds->isNotEmpty()) {
            PurchaseOrderItem::whereIn('id', $poItemIds)->update(['status_id' => 22]);
            ProcessingItem::whereIn('purchase_order_item_id', $poItemIds)->update(['status_id' => 1]);
        }

        $jobCard->update([
            'status_id' => 5,
        ]);

        DB::commit();

        return response()->json([
            'class'      => 'bg-success',
            'error'      => false,
            'message'    => 'Job Card cancelled successfully. Stock restored.',
            'call_back'  => route('admin.job-card.index'),
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'class'   => 'bg-danger',
            'error'   => true,
            'message' => $e->getMessage(),
        ], 500);
    }
}

public function assign(Request $request)
{
    $jobCard = JobCard::findOrFail($request->id);

    DB::transaction(function () use ($jobCard) {

        $stage = JobCardStage::where('job_card_id', $jobCard->id)
            ->where('name', 'PaperCutting')
            ->firstOrFail();

        $operator = $stage->operator_id
            ? Operator::find($stage->operator_id)
            : null;

        $paperCutting = PaperCutting::firstOrNew([
            'job_card_id'       => $jobCard->id,
            'job_card_stage_id' => $stage->id,
        ]);

        $paperCutting->fill([
            'counter'     => $jobCard->required_sheet ?? 0,
            'operator_id' => $operator?->id,
            'admin_id'    => $operator?->admin_id,
            'status_id'   => 1,
        ])->save();

        $stage->update([
            'in_counter' => ($jobCard->required_sheet ?? 0) + ($jobCard->wastage_sheet ?? 0),
        ]);

        $jobCard->update(['status_id' => 25]);

        $purchaseOrderItemIds = JobCardItem::where('job_card_id', $jobCard->id)
            ->pluck('purchase_order_item_id');

        if ($purchaseOrderItemIds->isNotEmpty()) {
            PurchaseOrderItem::whereIn('id', $purchaseOrderItemIds)
                ->update(['status_id' => 25]);
        }
    });

    return response()->json([
        'message' => 'Job Card Assigned to Paper Cutting.',
        'class'   => 'bg-success'
    ]);
}


public function cancel(Request $request)
{
    $request->validate([
        'id' => 'required|integer|exists:job_cards,id',
    ]);

    DB::transaction(function () use ($request) {

        $jobCard = JobCard::findOrFail($request->id);

        // Current stage (Paper Cutting)
        $stage = JobCardStage::where('job_card_id', $jobCard->id)
            ->where('name', 'PaperCutting')
            ->firstOrFail();

        // Find next stage
        $nextStage = JobCardStage::where('job_card_id', $jobCard->id)
            ->where('order', '>', $stage->order)
            ->orderBy('order')
            ->first();

        // ❌ If next stage already started / completed → block cancel
        if ($nextStage && $nextStage->status_id != 1) {
            throw new \Exception('Next stage already processed. Cancellation not allowed.');
        }

        // Reset current stage
        $stage->update([
            'status_id'   => 1,
            'in_counter'  => 0,
            'out_counter' => 0,
        ]);

        // Delete PaperCutting records
        PaperCutting::where([
            'job_card_id'       => $jobCard->id,
            'job_card_stage_id' => $stage->id,
        ])->delete();

        // Reset Job Card status (Assigned / Planned)
        $jobCard->update([
            'status_id' => 23,
        ]);

        // Reset related PO item statuses
        $purchaseOrderItemIds = JobCardItem::where('job_card_id', $jobCard->id)
            ->whereNotNull('purchase_order_item_id')
            ->pluck('purchase_order_item_id')
            ->unique();

        if ($purchaseOrderItemIds->isNotEmpty()) {
            PurchaseOrderItem::whereIn('id', $purchaseOrderItemIds)
                ->update(['status_id' => 24]);
        }
        $jobCard->items()->update(['status_id' => 5]);
    });

    return response()->json([
        'message' => 'Job Card cancelled from Paper Cutting successfully.',
        'class'   => 'bg-success',
    ]);
}



    public function addOperator(Request $request){
        $jobCard = JobCard::findOrFail($request->id);
        $stages = JobCardStage::where('job_card_id', $jobCard->id)->get();
        return view('admin.job-card.add-oprators', compact('jobCard', 'stages'));
    }

    public function updateOperator(Request $request, $jobCardId){
        $request->validate([
            'stage'    => 'required|array',
            'operator' => 'required|array',
        ]);

        DB::transaction(function () use ($request, $jobCardId) {

            foreach ($request->stage as $index => $stageId) {

                $operatorId = $request->operator[$index] ?? null;

                if (!$operatorId) {
                    continue;
                }

                $record = JobCardStage::where([
                    'job_card_id'       => $jobCardId,
                    'id' => $stageId,
                ])->first();

                if (!$record) {
                    throw new \Exception('Job Stage not found.');
                }

                $record->update([
                    'operator_id' => $operatorId,
                ]);
            }
        });

        return response()->json([
            'error'         => false,
            'class'         => 'bg-success',
            'message'       => 'Operators updated successfully',
            'table_refresh' => true,
            'model_id'      => 'dataSave',
        ]);
    }



    public function downloadPdf($id){
        $job_card = JobCard::with([
            'dye',
            'items.item.mfgBy',
            'items.item.mkdtBy',
            'items.purchaseOrder',
            'items.itemProcessDetail.coatingType',
            'items.itemProcessDetail.otherCoatingType',
            'jobCardProducts.product.productType',
        ])->findOrFail($id);

        $pdf = Pdf::loadView('admin.job-card.pdf', compact('job_card'))->setPaper('A4', 'landscape');

        $fileName = "job-card-{$job_card->set_number}.pdf";

        return $pdf->download($fileName);
    }



    public function store(Request $request){
        $jobCard = JobCard::findOrFail($request->id);

        $job_card_download = session()->get('job_card', []);

        if (isset($job_card_download[$request->id])) {
            unset($job_card_download[$request->id]);
            session()->put('job_card', $job_card_download);

            return response()->json([
                'message' => 'Removed from download job card Successfully',
                'class' => 'bg-warning',
                'added' => false
            ]);
        }


        $job_card_download[$request->id] = [
            "job_card_id" => $jobCard->id,
            "user_id" => auth('admin')->id(),
        ];

        session()->put('job_card', $job_card_download);

        return response()->json([
            'message' => 'Added to download job card Successfully',
            'class' => 'bg-success',
            'added' => true
        ]);
    }




public function selectedDownload(Request $request)
{
    $job_card_download = session()->get('job_card', []);

    if (!is_array($job_card_download) || count($job_card_download) == 0) {
        return redirect()->back()->with([
            'class' => 'bg-danger',
            'message' => 'No Job Card selected for download'
        ]);
    }

    $ids = collect($job_card_download)
        ->pluck('job_card_id')
        ->filter()
        ->unique()
        ->values()
        ->toArray();

    if (count($ids) == 0) {
        return redirect()->back()->with([
            'class' => 'bg-danger',
            'message' => 'No Job Card selected for download'
        ]);
    }

    $job_cards = JobCard::with([
        'dye',
        'items.coatingType',
        'items.otherCoatingType',
        'items.purchaseOrder',
        'items.item.mfgBy',
        'items.item.mkdtBy',
        'jobCardProducts.product.productType',
    ])
    ->whereIn('id', $ids)
    ->orderBy('id', 'desc')
    ->get();

    if ($job_cards->isEmpty()) {
        return redirect()->back()->with([
            'class' => 'bg-danger',
            'message' => 'Job Card Not Found.'
        ]);
    }

    $pdf = Pdf::loadView('admin.job-card.selected-pdf', compact('job_cards'))
        ->setPaper('A4', 'landscape');

    $fileName = "job-cards-" . now()->format('d-m-Y') . ".pdf";

    session()->forget('job_card');

    return $pdf->download($fileName);
}




}




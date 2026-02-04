<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OrderSheetExport;
use App\Exports\PurchaseOrderItemExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\OrderSheet\OrderSheetCollection;
use App\Models\Item;
use App\Models\ItemProcessDetail;
use App\Models\JobCard;
use App\Models\JobCardItem;
use App\Models\OrderSheet;
use App\Models\Processing;
use App\Models\ProcessingItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Services\JobCardWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class OrderSheetController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $orderSheet = session('order_sheet'); 
            $query = OrderSheet::with(['purchaseOrder', 'purchaseOrderItem', 'item']);

            if (!empty($orderSheet)) {
                $orderSheetIds = array_column($orderSheet, 'order_sheet_id');
                $query->orderByRaw("FIELD(id, " . implode(',', $orderSheetIds) . ") DESC");
            }

            $datas = $query->orderByRaw("CASE
                        WHEN status_id = 1 THEN 1
                        WHEN status_id = 3 THEN 2
                        ELSE 3
                    END")
                    ->orderBy('created_at', 'desc');
                    
            
            
            if ($request->filled('client')) {
                $datas->whereHas('item', function ($q) use ($request) {
                    $q->where('mkdt_by', $request->client);
                });
            }

            if ($request->filled('mkdt_by')) {
                $datas->whereHas('item', function ($q) use ($request) {
                    $q->where('mkdt_by', $request->mkdt_by);
                });
            }

            if ($request->filled('mfg_by')) {
                $datas->whereHas('item', function ($q) use ($request) {
                    $q->where('mfg_by', $request->mfg_by);
                });
            }

            if ($request->filled('item_name')) {
                $datas->whereHas('item', function ($q) use ($request) {
                    $q->where('item_name', 'LIKE', '%'.$request->item_name.'%');
                });
            }


            if ($request->filled('item_size')) {
                $datas->whereHas('item', function ($q) use ($request) {
                    $q->where('item_size', 'LIKE', '%'.$request->item_size.'%');
                });
            }

            if ($request->filled('set_no')) {
                $datas->whereHas('item.lastItem', function ($q) use ($request) {
                    $q->where('set_number', 'like', '%' . $request->set_no . '%');
                });
            }

            $status = $request->input('status');
            if ($status) {
                $datas->where('status_id', $status);
            }



            $totaldata = $datas->count();
            $request->merge(['recordsTotal' => $totaldata, 'length' => $request->length]);

            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new OrderSheetCollection($datas));
        }
        return view('admin.order-sheet.list');
    }


    public function create()
    {
        return view('admin.order-sheet.create');
    }






public function store(Request $request)
{
    $processing = OrderSheet::with(['purchaseOrderItem.purchaseOrder'])->findOrFail($request->id);

    // Check if current job_type exists
    if (!$processing->job_type) {
        return response()->json([
            'message' => 'This job card does not have a Job Type. Please check.',
            'class' => 'bg-warning',
            'added' => false
        ]);
    }

    $orderSheet = session()->get('order_sheet', []);

    // Case 1: Remove if already added
    if (isset($orderSheet[$request->id])) {
        unset($orderSheet[$request->id]);
        session()->put('order_sheet', $orderSheet);

        return response()->json([
            'message' => 'Removed from Processing Successfully',
            'class' => 'bg-warning',
            'added' => false
        ]);
    }

    // ✅ Case 2: If session has existing items → check attribute compatibility
    if (!empty($orderSheet)) {
        $existingIds = array_column($orderSheet, 'order_sheet_id');
        $existingItems = OrderSheet::with(['purchaseOrderItem.purchaseOrder'])
            ->whereIn('id', $existingIds)
            ->get();

        // Get current new PO
        $newPoItem = $processing->purchaseOrderItem;
        $newPo = $newPoItem->purchaseOrder;

        // foreach ($existingItems as $existing) {
        //     $poItem = $existing->purchaseOrderItem;
        //     $po = $poItem->purchaseOrder;

        //     // Job Type must be same
        //     if ($existing->job_type != $processing->job_type) {
        //         return response()->json([
        //             'message' => 'All selected job cards must have the same Job Type.',
        //             'class' => 'bg-danger',
        //             'added' => false
        //         ]);
        //     }

        //     // Check coating, paper type, gsm, etc.
        //     $diffs = [];

        //     if ($poItem->product_type_id != $newPoItem->product_type_id)
        //         $diffs[] = 'Product Type';
        //     if ($poItem->gsm != $newPoItem->gsm)
        //         $diffs[] = 'GSM';
        //     if ($poItem->other_coating != $newPoItem->other_coating)
        //         $diffs[] = 'Other Coating';
        //     if ($poItem->embossing != $newPoItem->embossing)
        //         $diffs[] = 'Embossing';
        //     if ($poItem->leafing != $newPoItem->leafing)
        //         $diffs[] = 'Leafing';
        //     if ($poItem->coating != $newPoItem->coating)
        //         $diffs[] = 'Coating';

        //     // If any mismatch found → reject
        //     if (count($diffs) > 0) {
        //         return response()->json([
        //             'message' => 'Mismatch found in: ' . implode(', ', $diffs) . '. All selected items must have the same coating type, paper type, and GSM.',
        //             'class' => 'bg-danger',
        //             'added' => false
        //         ]);
        //     }
        // }
    }


    $orderSheet[$request->id] = [
        "order_sheet_id" => $processing->id,
        "user_id" => auth('admin')->id(),
    ];

    session()->put('order_sheet', $orderSheet);

    return response()->json([
        'message' => 'Added to Processing Successfully',
        'class' => 'bg-success',
        'added' => true
    ]);
}





    public function createProcessing(Request $request){
        $request->validate([
            'designer'  => 'required|exists:admins,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $processing = Processing::create([
                    'added_by' => auth('admin')->id(),
                    'designer' => $request->designer,
                ]);
                $order_sheet = session('order_sheet', []);
                foreach ($order_sheet as $orderSheetId) {
                    $orderSheet = OrderSheet::with('purchaseOrderItem')->findOrFail($orderSheetId['order_sheet_id']);
                    $poItem = $orderSheet->purchaseOrderItem;

                    ProcessingItem::updateOrCreate(
                        [
                            'processing_id' => $processing->id,
                            'purchase_order_item_id' => $poItem->id,
                            'item_id' => $poItem->item_id,
                            'item_process_details_id'=> $poItem->itemProcessDetail->id,
                        ],
                        [
                            'purchase_order_id' => $poItem->purchase_order_id,
                            'urgent' => $orderSheet->urgent,
                            'job_type' => $orderSheet->job_type,
                            'quantity' => $orderSheet->final_quantity,
                            'ups' => $orderSheet->ups,
                            'status_id' => 1,
                        ]
                    );

                    $orderSheet->update(['status_id' => 3, 'designer' => $request->designer]);
                    $orderSheet->purchaseOrderItem->update(['status_id' => 22]);
                    ItemProcessDetail::where('purchase_order_item_id', $poItem->id)->update(['designer' => $request->designer]);
                }

                session()->forget('order_sheet');
            });

            return response()->json([
                'class'          => 'bg-success',
                'error'          => false,
                'message'        => 'Processing created successfully.',
                'table_refresh'  => true,
                'call_back'      => '',
                'model_id'       => 'dataSave',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'class' => 'bg-danger',
                'error' => true,
                'message' => $e->getMessage(),
                'table_referesh' => true,
                'model_id' => ''
            ]);
        }
    }





    public function updateFinalQuantity(Request $request){ 
        if($request->final_quantity){
            $order_sheet = OrderSheet::where('id', $request->id)->first();
            OrderSheet::where('id', $request->id)->update(['final_quantity' => $request->final_quantity, 'quantity_status' => 1]);
            $item_process = ItemProcessDetail::where('purchase_order_item_id', $order_sheet->purchase_order_item_id)->first();

            $quantity = $request->final_quantity;
            $rate = $item_process->rate;
            $gstPercentage = $item_process->rate;

            $amount      = $quantity * $rate;
            $gstAmount   = ($amount * $gstPercentage) / 100;
            $totalAmount = $amount + $gstAmount;


            $item_process->update([
                'quantity'          => $quantity,
                'rate'              => $rate,
                'gst_percentage'    => $gstPercentage,
                'amount'            => $amount,
                'gst_amount'        => $gstAmount,
                'total_amount'      => $totalAmount,
            ]);
            return response()->json([
                'message' => 'Final Quantity Updated successfully.',
                'title' => 'Order Sheet Updated.',
                'class' => 'bg-success'
            ]);
        }
        return response()->json([
            'message' => 'Something went wrong.',
            'title' => 'Order Sheet.',
            'class' => 'bg-ganger'
        ]);
    }


    public function updateJobType(Request $request){ 
        if($request->job_type){
            OrderSheet::where('id', $request->id)->update(['job_type' => $request->job_type, 'job_type_status' => 1]);
            $order_sheet = OrderSheet::where('id', $request->id)->first();
            ItemProcessDetail::where('purchase_order_item_id', $order_sheet->purchase_order_item_id)->update(['job_type' => $request->job_type]);

            return response()->json([
                'message' => 'Job Type Updated successfully.',
                'title' => 'Order Sheet Updated.',
                'class' => 'bg-success'
            ]);
        }
        return response()->json([
            'message' => 'Something went wrong.',
            'title' => 'Order Sheet.',
            'class' => 'bg-ganger'
        ]);
    }



    public function updateUrgent(Request $request){ 
        if($request->urgent){
            OrderSheet::where('id', $request->id)->update(['urgent' => $request->urgent]);

            $order_sheet = OrderSheet::where('id', $request->id)->first();
            ItemProcessDetail::where('purchase_order_item_id', $order_sheet->purchase_order_item_id)->update(['urgent' => $request->urgent]);
            
            return response()->json([
                'message' => 'Urgent Updated successfully.',
                'title' => 'Order Sheet Updated.',
                'class' => 'bg-success'
            ]);
        }
        return response()->json([
            'message' => 'Something went wrong.',
            'title' => 'Order Sheet.',
            'class' => 'bg-ganger'
        ]);
    }


    public function updateUps(Request $request){ 
        if($request->ups){
            OrderSheet::where('id', $request->id)->update(['ups' => $request->ups, 'ups_status' => 1]);

            $processing = OrderSheet::where('id', $request->id)->first();
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
            'class' => 'bg-ganger'
        ]);
    }


    public function updateGSM(Request $request){ 
        if($request->gsm){
            OrderSheet::where('id', $request->id)->update(['gsm_status' => 1]);

            $processing = OrderSheet::where('id', $request->id)->first();
            ItemProcessDetail::where('purchase_order_item_id', $processing->purchase_order_item_id)->update(['gsm' => $request->gsm]);
            
            return response()->json([
                'message' => 'GSM Updated successfully.',
                'title' => 'Processing Updated.',
                'class' => 'bg-success'
            ]);
        }
        return response()->json([
            'message' => 'Something went wrong.',
            'title' => 'Processing.',
            'class' => 'bg-ganger'
        ]);
    }


   

    public function back(Request $request){
        if (!$request->id) {
            return response()->json([
                'message' => 'Something went wrong.',
                'title'   => 'Order Sheet.',
                'class'   => 'bg-danger'
            ]);
        }

        return DB::transaction(function () use ($request) {

            $orderSheet = OrderSheet::where('id', $request->id)
                ->where('status_id', 1)
                ->first();

            if (!$orderSheet) {
                return response()->json([
                    'message' => 'Order Sheet may compled.',
                    'title'   => 'Order sheet not found.',
                    'class'   => 'bg-warning'
                ]);
            }

            // Update PO item status
            PurchaseOrderItem::where('id', $orderSheet->purchase_order_item_id)
                ->update(['status_id' => 1]);

            $purchaseOrderId = $orderSheet->purchase_order_id;

            // Delete order sheet
            $orderSheet->delete();

            // 🔥 CHECK ALL PO ITEMS STATUS
            $hasPendingItems = PurchaseOrderItem::where('purchase_order_id', $purchaseOrderId)
                ->where('status_id', '!=', 1)
                ->exists();

            // 🔁 UPDATE PO STATUS
            PurchaseOrder::where('id', $purchaseOrderId)
                ->update([
                    'status_id' => $hasPendingItems ? 2 : 1
                ]);

            return response()->json([
                'message' => 'Item Back In PO Successfully.',
                'title'   => 'Processing Updated.',
                'class'   => 'bg-success'
            ]);
        });
    }


    public function exportForm(){
        return view('admin.order-sheet.export');
    }


    public function export(Request $request){
        $filters = $request->only([
            'export_status',
            'export_po_date',
            'export_clients',
            'export_mfg_by',
            'export_mkdt_by',
        ]);

        $filename = 'order_sheet.xlsx';

        Excel::store(
            new OrderSheetExport($filters),
            'excel/' . $filename,
            'public'
        );

        return response()->json([
            'message'  => 'Order Sheet exported successfully.',
            'filename' => asset('storage/excel/' . $filename),
            'class'    => 'success'
        ]);
    }



}




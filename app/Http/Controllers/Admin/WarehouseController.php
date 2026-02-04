<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Warehouse\WarehouseCollection;
use App\Models\Item;
use App\Models\ItemForBilling;
use App\Models\ItemStock;
use App\Models\JobCardStage;
use App\Models\Operator;
use App\Models\PurchaseOrderItem;
use App\Models\Warehouse;
use App\Models\WarehouseItem;
use App\Services\JobCardStageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $admin = auth('admin')->user();

            $datas = Warehouse::orderByRaw("
                CASE
                WHEN status_id = 1 THEN 1
                WHEN status_id = 3 THEN 2
                ELSE 3
                END
                ")
            ->orderBy('created_at', 'desc');

            if ($admin->listing_type === 'Own') {
                $datas->where(function ($q) use ($admin) {
                    $q->where('admin_id', $admin->id)
                    ->orWhereNull('admin_id');
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

            $status = $request->input('status');
            if ($status) {
                $datas->where('status_id', $status);
            }
            
            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new WarehouseCollection($datas));
        }
        return view('admin.warehouse.list');
    }


    public function cancel(Request $request){
        try {
            $warehouse = Warehouse::findOrFail($request->id);
            JobCardStageService::cancelStage($warehouse->stage);

            return response()->json([
                'message' => 'Job Card successfully cancelled and moved back to previous stage.',
                'class'   => 'bg-success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'class'   => 'bg-danger'
            ], 400);
        }
    }


    public function addDetails($id){
        $warehouse = Warehouse::findOrFail($id);
        return view('admin.warehouse.add-details', compact('warehouse'));
    }



    
    public function updateDetails(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $items = $request->input('kt_docs_repeater_advanced', []);
        $item = Item::findOrFail($warehouse->item_id);

        DB::transaction(function () use ($warehouse, $items, $item) {

            foreach ($items as $row) {

                if (!isset($row['quantity_per_box'], $row['number_of_box'])) {
                    continue;
                }

                $quantityPerBox = (int) $row['quantity_per_box'];
                $deliveredBoxes = (int) $row['number_of_box'];
                $deliveredQty   = $quantityPerBox * $deliveredBoxes;

                $warehouseItem = WarehouseItem::where('warehouse_id', $warehouse->id)
                ->where('status_id', '!=', 3)
                ->lockForUpdate()
                ->first();

                if (! $warehouseItem) {
                    continue;
                }

                if ($deliveredBoxes > (int) $warehouseItem->pending_number_of_box) {
                    throw new \Exception('Delivered boxes cannot exceed pending boxes');
                }

                $warehouseItem->delivered_number_of_box += $deliveredBoxes;
                $warehouseItem->pending_number_of_box   -= $deliveredBoxes;

                $warehouseItem->billed_quantity  += $deliveredQty;
                $warehouseItem->pending_quantity -= $deliveredQty;

                if ((int) $warehouseItem->pending_number_of_box <= 0) {
                    $warehouseItem->pending_number_of_box = 0;
                    $warehouseItem->status_id = 3;
                }

                $warehouseItem->save();

                $itemForBilling = ItemForBilling::create(
                    [
                        'purchase_order_id'      => $warehouse->purchase_order_id,
                        'purchase_order_item_id' => $warehouse->purchase_order_item_id,
                        'job_card_id'            => $warehouse->job_card_id,
                        'job_card_item_id'       => $warehouse->job_card_item_id,
                        'item_id'                => $warehouse->item_id,
                        'status_id'              => 1,
                        'mkdt_by'               => $item->mkdt_by,
                        'mfg_by'                => $item->mfg_by,
                        'product_type_id'       => $item->product_type_id,
                        'coating_type_id'       => $item->coating_type_id,
                        'other_coating_type_id' => $item->other_coating_type_id,
                        'item_name'             => $item->item_name,
                        'item_size'             => $item->item_size,
                        'colour'                => $item->colour,
                        'gsm'                   => $item->gsm,
                        'embossing'             => $item->embossing,
                        'leafing'               => $item->leafing,
                        'back_print'            => $item->back_print,
                        'braille'               => $item->braille,
                        'artwork_code'          => $item->artwork_code,
                        'quantity_per_box'      => $quantityPerBox,
                        'number_of_box'         => $deliveredBoxes,
                        'total_quantity'        => $deliveredQty,
                    ]
                );

                $stock = ItemStock::lockForUpdate()->firstOrNew([
                    'item_id' => $itemForBilling->item_id,
                ]);

                $stock->total_quantity = (int) ($stock->total_quantity ?? 0)
                - (int) $itemForBilling->total_quantity;

                $stock->save();
            }

            $allCompleted = WarehouseItem::where('warehouse_id', $warehouse->id)
            ->where('status_id', '!=', 3)
            ->doesntExist();

            if ($allCompleted) {
                $warehouse->status_id = 3;
                $warehouse->save();

                PurchaseOrderItem::where('id', $warehouse->purchase_order_item_id)
                ->update(['status_id' => 35]);
            }
        });

        return response()->json([
            'class'         => 'bg-success',
            'error'         => false,
            'message'       => 'Details updated successfully.',
            'table_refresh' => true,
            'call_back'     => '',
            'model_id'      => 'dataSave',
        ]);
    }

    

    

}

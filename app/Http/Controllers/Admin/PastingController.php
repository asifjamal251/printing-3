<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Pasting\PastingCollection;
use App\Models\ItemStock;
use App\Models\JobCardStage;
use App\Models\Operator;
use App\Models\Pasting;
use App\Models\PastingItem;
use App\Models\Warehouse;
use App\Models\WarehouseItem;
use App\Services\JobCardStageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PastingController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $admin = auth('admin')->user();

            $datas = Pasting::orderByRaw("
                    CASE
                        WHEN status_id = 1 THEN 1
                        WHEN status_id = 2 THEN 2
                        WHEN status_id = 3 THEN 3
                        ELSE 4
                    END
                ")
                ->orderBy('created_at', 'desc');

            if ($admin->listing_type === 'Own') {
                $datas->where(function ($q) use ($admin) {
                    $q->where('admin_id', $admin->id)
                      ->orWhereNull('admin_id');
                });
            }


            if ($request->filled('operator')) {
                $datas->where('operator_id', $request->operator);
            }


            if ($request->filled('set_no')) {
                $datas->whereHas('jobCard', function ($q) use ($request) {
                    $q->where('set_number', 'LIKE', '%' . $request->set_no . '%');
                });
            }

            if ($request->filled('dye')) {
                $datas->whereHas('jobCard', function ($q) use ($request) {
                    $q->where('dye_id', 'LIKE', '%' . $request->dye . '%');
                });
            }

            if ($request->filled('mkdt_by') || $request->filled('mfg_by') || $request->filled('item_name') || $request->filled('item_size')) {
                $datas->whereHas('jobCard.items', function ($q) use ($request) {

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

            $status = $request->input('status');
            if ($status) {
                $datas->where('status_id', $status);
            } else{
                $datas->whereIn('status_id', [1,2]);
            }
            
            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new PastingCollection($datas));
        }
        return view('admin.pasting.list');
    }

    public function updateOperator(Request $request){
        if($request->operator_id){
            $pasting = Pasting::where('id', $request->id)->first();
            $operator = Operator::where('id', $request->operator_id)->first();
            $pasting->update(['operator_id' => $operator->id, 'admin_id' => $operator->admin_id, 'status_id' => 2]);
            JobCardStage::where('id', $pasting->job_card_stage_id)->update(['operator_id' => $operator->id]);
            return response()->json([
                'message' => 'Operator Updated successfully.',
                'title' => 'Pasting Updated.',
                'class' => 'bg-success'
            ]);
        }
        return response()->json([
            'message' => 'Something went wrong.',
            'title' => 'Pasting.',
            'class' => 'bg-danger'
        ]);
    }

    public function updateCounter(Request $request){
        if (!$request->counter) {
            return response()->json([
                'message' => 'Counter is required.',
                'title'   => 'Pasting',
                'class'   => 'bg-danger'
            ]);
        }

        try {

            DB::transaction(function () use ($request) {

                $pasting = Pasting::findOrFail($request->id);

                if (
                    empty($pasting->operator_id) ||
                    empty($pasting->admin_id)
                ) {
                    throw new \Exception('Operator is not assigned.');
                }

                JobCardStageService::updateStageCounter(
                    $pasting->stage,
                    (int) $request->counter
                );

                $pasting->update([
                    'counter'       => $request->counter,
                    'completed_at'  => now(),
                    'completed_by'  => auth('admin')->user()->id,
                    'status_id'     => 3,
                ]);
            });

        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage(),
                'title'   => 'Pasting',
                'class'   => 'bg-danger'
            ]);
        }

        return response()->json([
            'message' => 'Counter Updated successfully.',
            'title'   => 'Pasting Updated.',
            'class'   => 'bg-success'
        ]);
    }


    public function cancel(Request $request){
        try {
            $pasting = Pasting::findOrFail($request->id);
            JobCardStageService::cancelStage($pasting->stage);

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
        $pasting = Pasting::findOrFail($id);
        return view('admin.pasting.add-details', compact('pasting'));
    }




    public function updateDetails(Request $request, $id)
    {
        $pasting = Pasting::with('items')->findOrFail($id);

        if (
            empty($pasting->operator_id) ||
            empty($pasting->admin_id)
        ) {
            throw new \Exception('Operator is not assigned.');
        }

        $items = $request->input('kt_docs_repeater_advanced', []);

        DB::transaction(function () use ($pasting, $items) {
            $existingIds = $pasting->items->pluck('id')->toArray();
            $requestIds = collect($items)->pluck('id')->filter()->toArray();
            $deleteIds = array_diff($existingIds, $requestIds);

            if (!empty($deleteIds)) {
                PastingItem::whereIn('id', $deleteIds)->delete();
            }

            foreach ($items as $row) {
                if (
                    empty($row['quantity_per_box']) &&
                    empty($row['number_of_box'])
                ) {
                    continue;
                }
                PastingItem::updateOrCreate(
                    [
                        'id' => $row['id'] ?? null,
                    ],
                    [
                        'pasting_id'       => $pasting->id,
                        'quantity_per_box' => (int) $row['quantity_per_box'],
                        'number_of_box'    => (int) $row['number_of_box'],
                    ]
                );
            }
        });

        return response()->json([
            'class' => 'bg-success',
            'error' => false,
            'message' => 'Details Added successfully.',
            'table_refresh' => true,
            'call_back' => '',
            'model_id' => 'dataSave',
        ]);
    }


    public function sendWarehouse(Request $request, $id){
        $pasting = Pasting::with([
                'items',
                'jobCard',
                'jobCard.items',
                'jobCardItem.item',
                'purchaseOrderItem'
            ])
            ->where('id', $id)
            ->where('status_id', 2)
            ->first();

        if (!$pasting) {
            return response()->json([
                'message' => 'Please Add Details.',
                'class'   => 'bg-warning',
            ]);
        }

        DB::transaction(function () use ($pasting) {

            foreach ($pasting->items as $item) {

                $warehouse = Warehouse::firstOrCreate(
                    [
                        'item_id'                => $pasting->jobCardItem->item->id,
                        'mkdt_by'                => $pasting->jobCardItem->item->mkdt_by,
                        'mfg_by'                 => $pasting->jobCardItem->item->mfg_by,
                        'purchase_order_id'      => $pasting->jobCardItem->purchase_order_id,
                        'purchase_order_item_id' => $pasting->jobCardItem->purchase_order_item_id,
                        'job_card_item_id'       => $pasting->jobCardItem->id,
                        'job_card_id'            => $pasting->jobCardItem->job_card_id,
                        'status_id' => 1,
                    ]
                );

                $existing = WarehouseItem::where([
                    'warehouse_id'     => $warehouse->id,
                    'quantity_per_box' => $item->quantity_per_box ?? 0,
                ])->first();

                if ($existing) {
                    $existing->update([
                        'pending_number_of_box' => $existing->number_of_box + ($item->number_of_box ?? 0),
                        'status_id'     => 1,
                    ]);
                } else {
                    WarehouseItem::create([
                        'warehouse_id'     => $warehouse->id,
                        'quantity_per_box' => $item->quantity_per_box ?? 0,
                        'pending_number_of_box'    => $item->number_of_box ?? 0,
                        'status_id'        => 1,
                    ]);
                }

                $stock = ItemStock::firstOrNew([
                    'item_id' => $pasting->jobCardItem->item->id,
                ]);
                $stock->total_quantity = (int) ($stock->total_quantity ?? 0) + (int) ($item->total_quantity ?? 0);
                $stock->save();
                $item->update(['status_id' => 3]);
            }

        });

        return response()->json([
            'message' => 'Items successfully sent to warehouse.',
            'class'   => 'bg-success',
        ]);
    }



    public function completed(Request $request, $id){
        $pasting = Pasting::with([
            'items',
            'jobCard',
            'jobCard.items',
            'jobCardItem.item',
            'purchaseOrderItem'
        ])->where('id', $id)->where('status_id', 2)->whereDoesntHave('items', function ($q) {
            $q->where('status_id', '!=', 3);
        })->first();

        if (!$pasting) {
            return response()->json([
                'message' => 'First check details and must be all item send to warehouse.',
                'class'   => 'bg-warning',
            ]);
        }

        DB::transaction(function () use ($pasting) {

            $pasting->jobCardItem->update(['status_id' => 3]);
            $pasting?->jobCardItem?->itemProcessDetail->update(['status_id' => 3]);

            $pendingItemsCount = $pasting->jobCard->items()
                ->where('status_id', '!=', 3)
                ->count();

            if ($pendingItemsCount === 0) {
                $pasting->jobCard->update(['status_id' => 3]);
            }

            if ($pasting->purchaseOrderItem) {
                $pasting->purchaseOrderItem->update(['status_id' => 34]);
            }

            $pasting->update(['status_id' => 3]);
        });

        return response()->json([
            'message' => 'Items successfully completed.',
            'class'   => 'bg-success',
        ]);
    }


    

}

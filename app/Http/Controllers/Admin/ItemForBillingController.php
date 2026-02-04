<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ItemForBilling\ItemForBillingCollection;
use App\Models\Item;
use App\Models\ItemForBilling;
use App\Models\ItemStock;
use App\Models\JobCardStage;
use App\Models\Operator;
use App\Models\ItemForBillingItem;
use App\Services\JobCardStageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemForBillingController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $admin = auth('admin')->user();

            $datas = ItemForBilling::orderByRaw("
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

            return response()->json(new ItemForBillingCollection($datas));
        }
        return view('admin.item-for-billing.list');
    }


    public function addedForBilling(Request $request){
        $itemForBilling = ItemForBilling::findOrFail($request->id);

        $billingItems = session()->get('item_for_billing', []);

        if (isset($billingItems[$request->id])) {
            unset($billingItems[$request->id]);
            session()->put('item_for_billing', $billingItems);

            return response()->json([
                'message' => 'Removed from billing process successfully',
                'class'   => 'bg-warning',
                'added'   => false
            ]);
        }

        // if (!empty($billingItems)) {
        //     $firstItem = ItemForBilling::find(
        //         reset($billingItems)['item_for_billing_id']
        //     );

        //     if (
        //         $firstItem->mkdt_by !== $itemForBilling->mkdt_by ||
        //         $firstItem->mfg_by  !== $itemForBilling->mfg_by
        //     ) {
        //         return response()->json([
        //             'message' => 'Marketing and Manufacturing client must be same for billing',
        //             'class'   => 'bg-danger',
        //             'error'   => true
        //         ]);
        //     }
        // }

        $billingItems[$request->id] = [
            'item_for_billing_id' => $itemForBilling->id,
            'user_id'             => auth('admin')->id(),
            'mkdt_by'             => $itemForBilling->mkdt_by,
            'mfg_by'              => $itemForBilling->mfg_by,
        ];

        session()->put('item_for_billing', $billingItems);

        return response()->json([
            'message' => 'Added to billing successfully',
            'class'   => 'bg-success',
            'added'   => true
        ]);
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


    public function create(){
        $billingItems = session()->get('item_for_billing', []);


        if (empty($billingItems)) {
            return response()->json([
                    'message' => 'Please add at least one item for billing first.',
                    'class'   => 'bg-danger',
                    'error'   => true
                ]);
        }

        $firstItem = reset($billingItems);

        return view('admin.item-for-billing.create', [
            'mkdt_by' => $firstItem['mkdt_by'],
            'mfg_by'  => $firstItem['mfg_by'],
            'items'   => $billingItems,
        ]);
    }



    

    

}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Billing\BillingCollection;
use App\Models\Billing;
use App\Models\BillingItem;
use App\Models\ItemForBilling;
use App\Models\ItemStock;
use App\Models\JobCardItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class BillingController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $admin = auth('admin')->user();

            $datas = Billing::orderByRaw(" CASE
                WHEN status_id = 1 THEN 1
                WHEN status_id = 3 THEN 2
                ELSE 3
                END")->orderBy('created_at', 'desc');

            if ($admin->listing_type === 'Own') {
                $datas->where(function ($q) use ($admin) {
                    $q->where('admin_id', $admin->id)
                    ->orWhereNull('admin_id');
                });
            }

            $totaldata = $datas->count();

            $status = $request->input('status');
            if ($status) {
                $datas->where('status_id', $status);
            }
            
            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new BillingCollection($datas));
        }
        return view('admin.billing.list');
    }






public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'bill_to'           => 'required|exists:clients,id',
        'ship_to'           => 'nullable|exists:clients,id',
        'bill_from'         => 'required|exists:firms,id',
        'bill_date'         => 'required|date',
        'bill_number'       => 'nullable|string|max:255',
        'invoice_number'    => 'nullable|string|max:255',
        'vehicle_no'        => 'nullable|string|max:255',
        'transporter_name'  => 'nullable|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'class' => 'bg-danger',
            'error' => true,
            'message' => $validator->errors()->first(),
        ], 422);
    }

    $items = session()->get('item_for_billing', []);

    if (empty($items)) {
        return response()->json([
            'class' => 'bg-danger',
            'error' => true,
            'message' => 'No items found for billing',
        ], 422);
    }

    DB::transaction(function () use ($request, $items) {

        $billing = Billing::create([
            'added_by' => auth()->id(),
            'bill_to' => $request->bill_to,
            'ship_to' => $request->ship_to,
            'firm_id' => $request->bill_from,
            'bill_date' => $request->bill_date,
            'bill_number' => $request->bill_number,
            'invoice_number' => $request->invoice_number,
            'vehicle_no' => $request->vehicle_no,
            'transporter_name' => $request->transporter_name,
            'status_id' => 1
        ]);

        foreach ($items as $row) {

            $itemForBilling = ItemForBilling::lockForUpdate()
                ->where('id', $row['item_for_billing_id'])
                ->where('status_id', '!=', 36)
                ->firstOrFail();

            BillingItem::create([
                'item_for_billing_id' => $itemForBilling->id,
                'item_id' => $itemForBilling->item_id,
                'purchase_order_id' => $itemForBilling->purchase_order_id,
                'purchase_order_item_id' => $itemForBilling->purchase_order_item_id,

                'job_card_id' => $itemForBilling->job_card_id,
                'job_card_item_id' => $itemForBilling->job_card_item_id,

                'product_type_id' => $itemForBilling->product_type_id,
                'coating_type_id' => $itemForBilling->coating_type_id,
                'other_coating_type_id' => $itemForBilling->other_coating_type_id,
                'item_name' => $itemForBilling->item_name,
                'item_size' => $itemForBilling->item_size,
                'colour' => $itemForBilling->colour,
                'gsm' => $itemForBilling->gsm,
                'embossing' => $itemForBilling->embossing,
                'leafing' => $itemForBilling->leafing,
                'back_print' => $itemForBilling->back_print,
                'braille' => $itemForBilling->braille,
                'artwork_code' => $itemForBilling->artwork_code,
                'quantity_per_box' => $itemForBilling->quantity_per_box,
                'number_of_box' => $itemForBilling->number_of_box,
                'total_quantity' => $itemForBilling->total_quantity
            ]);

            $itemForBilling->update(['status_id' => 36]);

            PurchaseOrderItem::where('id', $itemForBilling->purchase_order_item_id)->update(['status_id' => 36]);
            JobCardItem::where('id', $itemForBilling->job_card_item_id)->update(['status_id' => 3]);

            
            // $stock = ItemStock::lockForUpdate()->firstOrNew(['item_id' => $itemForBilling->item_id]);
            // $currentQty = (int) ($stock->total_quantity ?? 0);
            // $deductQty  = (int) ($itemForBilling->total_quantity ?? 0);
            // $newQty = $currentQty - $deductQty;
            // if ($newQty < 0) {
            //     throw new \Exception('Insufficient stock');
            // }
            // $stock->total_quantity = $newQty;
            // $stock->save();


            $poId = $itemForBilling->purchase_order_id;

            $pending = PurchaseOrderItem::where('purchase_order_id', $poId)
                ->where('status_id', '!=', 36)
                ->exists();

            if (! $pending) {
                PurchaseOrder::where('id', $poId)->update(['status_id' => 3]);
            }
        }

        session()->forget('item_for_billing');
    });

    return response()->json([
        'class' => 'bg-success',
        'error' => false,
        'message' => 'Billing created successfully',
        'table_refresh' => true,
        'call_back' => route('admin.billing.index'),
        'model_id' => 'dataSave',
    ]);
}


    

    

}

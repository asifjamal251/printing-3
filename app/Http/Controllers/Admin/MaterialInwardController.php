<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\MaterialInward\MaterialInwardCollection;
use App\Models\MaterialInward;
use App\Models\MaterialInwardItem;
use App\Models\MaterialOrder;
use App\Models\MaterialOrderItem;
use App\Models\ProductAttribute;
use App\Models\ProductLedger;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MaterialInwardController extends Controller{
    public function index(Request $request){
        
        if ($request->wantsJson()) {
            $datas = MaterialInward::orderByRaw("CASE
                WHEN status_id = 2 THEN 1
                WHEN status_id = 5 THEN 2
                ELSE 3
            END")->orderBy('receipt_no', 'desc');


            $status = $request->input('status');
            if ($status) {
                $datas->where('status_id', $status);
            }

            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new MaterialInwardCollection($datas));
        }
        return view('admin.material-inward.list'); 
    }



    public function create(Request $request){
        $order = MaterialOrder::with(['items' => function($query){
            $query->where(['status_id' => 1]);
        }])
            ->where('id', $request->order_id)
            ->whereIn('status_id', [1, 18])
            ->first();

        if (!$order) {
            return redirect()->back()->with([
                'class' => 'bg-danger',
                'message' => 'This Material Order cannot be Inward because it is already finalized.'
            ]);
        }

        return view('admin.material-inward.create', compact('order'));
        
    }

    

    

    public function store(Request $request){
        $validated = $request->validate([
            'order_id' => 'required|exists:material_orders,id',
            'vendor' => 'required|exists:vendors,id',
            'bill_no' => 'required|string',
            'bill_date' => 'required|date',

            'kt_docs_repeater_advanced' => 'required|array|min:1',
            'kt_docs_repeater_advanced.*.product' => 'required|exists:products,id',
            'kt_docs_repeater_advanced.*.quantity' => 'required|numeric|min:1',
            'kt_docs_repeater_advanced.*.item_per_packet' => 'required|exists:product_attributes,id',
            'kt_docs_repeater_advanced.*.weight_per_piece' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.total_weight' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.rate' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.gst' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.amount' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.order_item_id' => 'required|exists:material_order_items,id',
        ], [
            'vendor.required' => 'Please select a vendor.',
            'vendor.exists' => 'Selected vendor does not exist.',
            'bill_to.required' => 'Please select who to bill to.',
            'bill_to.exists' => 'Selected bill to client does not exist.',
            'ship_to.required' => 'Please select shipping address.',
            'ship_to.exists' => 'Selected shipping client does not exist.',
            'mo_date.required' => 'Please enter the material order date.',
            'mo_date.date' => 'The material order date is not valid.',

            'kt_docs_repeater_advanced.required' => 'Please add at least one product.',
            'kt_docs_repeater_advanced.min' => 'Please add at least one product item.',
            'kt_docs_repeater_advanced.*.product.required' => 'Product is required for each row.',
            'kt_docs_repeater_advanced.*.product.exists' => 'Selected product does not exist.',
            'kt_docs_repeater_advanced.*.quantity.required' => 'Quantity is required.',
            'kt_docs_repeater_advanced.*.quantity.numeric' => 'Quantity must be a number.',
            'kt_docs_repeater_advanced.*.quantity.min' => 'Quantity must be at least 1.',
            'kt_docs_repeater_advanced.*.item_per_packet.required' => 'Please select items per packet.',
            'kt_docs_repeater_advanced.*.item_per_packet.exists' => 'Selected item per packet is invalid.',
            'kt_docs_repeater_advanced.*.weight_per_piece.required' => 'Weight per piece is required.',
            'kt_docs_repeater_advanced.*.weight_per_piece.numeric' => 'Weight per piece must be numeric.',
            'kt_docs_repeater_advanced.*.total_weight.required' => 'Total weight is required.',
            'kt_docs_repeater_advanced.*.total_weight.numeric' => 'Total weight must be numeric.',
            'kt_docs_repeater_advanced.*.rate.required' => 'Rate is required.',
            'kt_docs_repeater_advanced.*.rate.numeric' => 'Rate must be numeric.',
            'kt_docs_repeater_advanced.*.gst.required' => 'GST percentage is required.',
            'kt_docs_repeater_advanced.*.gst.numeric' => 'GST must be a number.',
            'kt_docs_repeater_advanced.*.amount.required' => 'Amount is required.',
            'kt_docs_repeater_advanced.*.amount.numeric' => 'Amount must be numeric.',
            'kt_docs_repeater_advanced.*.order_item_id.required' => 'Order Item required.',
        ]);

        //dd($request->all());

        DB::beginTransaction();
       try {
            $subtotal = 0;
            $totalGst = 0;

            $materialOrder = MaterialOrder::where('id', $validated['order_id'])
            ->whereIn('status_id', [1, 18])
            ->first();
            if($materialOrder){
                $materialInward = MaterialInward::create([
                    'received_by' => auth('admin')->user()->id, 
                    'material_order_id' => $materialOrder->id,
                    'vendor_id' => $validated['vendor'],
                    'bill_no' => $validated['bill_no'],
                    'bill_date' => Carbon::parse($validated['bill_date'])->format('Y-m-d'),
                    'status_id' => 3,
                ]);

                foreach ($validated['kt_docs_repeater_advanced'] as $item) {
                    $materialOrderItem = MaterialOrderItem::where('id', $item['order_item_id'])->whereIn('status_id', [1])->first();

                    $product_attribute = ProductAttribute::findOrFail($item['item_per_packet']);

                    $stock = Stock::where(['product_attribute_id' => $item['item_per_packet'], 'product_id' => $item['product']])->first();

                    if($materialOrderItem){
                        $gstAmount = ($item['rate'] * $item['quantity']) * $item['gst'] / 100;

                        $materialInward->items()->create([
                            'product_id' => $item['product'],
                            'material_order_id' => $materialOrder->id,
                            'material_order_item_id' => $item['order_item_id'],
                            'product_attribute_id' => $item['item_per_packet'],
                            'quantity' => $item['quantity'],
                            'total_weight' => $item['total_weight'],
                            'rate' => $item['rate'],
                            'gst' => $item['gst'],
                            'gst_amount' => $gstAmount,
                            'amount' => $item['amount'],
                            'status_id' => 3,
                        ]);

                        $subtotal += $item['rate'] * $item['quantity'];
                        $totalGst += $gstAmount;

                        $old_stock = $stock->quantity;
                        $new_stock = $item['quantity'] * $product_attribute->item_per_packet;
                        $current_stock = $stock->quantity + $new_stock;

                        //dd($new_stock);
                        

                        ProductLedger::create([
                            'product_id' => $item['product'],
                            'product_attribute_id' => $item['item_per_packet'],

                            'reference_no' => $materialInward->receipt_no,
                            'type' => 'in',
                            'old_quantity' => $old_stock,
                            'new_quantity' => $new_stock,
                            'current_quantity' => $current_stock,
                            'source_type' => 'Material Inward',
                            'source_id' => $materialInward->id,
                            'note' => 'Material Inward from MO',
                            'created_by' => auth('admin')->user()->id, 
                        ]);

                        $stock->quantity = $current_stock;
                        $stock->save();

                        $materialOrderItem->status_id = 3;
                        $materialOrderItem->save();
                    }
                }

                $pendingItems = MaterialOrderItem::where([
                    'material_order_id' => $materialOrder->id,
                    'status_id' => 1
                ])->count();

                if ($pendingItems == 0) {
                    $materialOrder->status_id = 3;
                } else {
                    $materialOrder->status_id = 18;
                }
                $materialOrder->save();

                $materialInward->update([
                    'subtotal' => $subtotal,
                    'gst_total' => $totalGst,
                    'total' => $subtotal + $totalGst,
                ]);
            }

            DB::commit();
           return response()->json([
                'class' => 'bg-success',
                'error' => false,
                'message' => 'Material Order Saved Successfully',
                'call_back' => route('admin.material-inward.index'),
                'table_referesh' => true,
                'model_id' => 'dataSave'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'class' => 'bg-danger',
                'error' => true,
                'message' => 'Material Order Not Saved'. $e,
                'call_back' => '',
                'table_referesh' => true,
                'model_id' => '',
                'debug' => $e->getMessage()
            ]);
        }
    }

   public function edit($id){
        $order = MaterialInward::with('items')->findOrFail($id);
        return view('admin.material-inward.edit', compact('order'));
    }

    public function update(Request $request, $id){
        $validated = $request->validate([
            'order_id' => 'required|exists:material_orders,id',
            'vendor' => 'required|exists:vendors,id',
            'bill_no' => 'required|string',
            'bill_date' => 'required|date',
            'kt_docs_repeater_advanced' => 'required|array|min:1',
            'kt_docs_repeater_advanced.*.id' => 'required|exists:material_inward_items,id',
            'kt_docs_repeater_advanced.*.product' => 'required|exists:products,id',
            'kt_docs_repeater_advanced.*.quantity' => 'required|numeric|min:1',
            'kt_docs_repeater_advanced.*.item_per_packet' => 'required|exists:product_attributes,id',
            'kt_docs_repeater_advanced.*.weight_per_piece' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.total_weight' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.rate' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.gst' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.amount' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.order_item_id' => 'required|exists:material_order_items,id',
            'kt_docs_repeater_advanced.*.old_quantity' => 'required|numeric',
        ], [
            'vendor.required' => 'Please select a vendor.',
            'vendor.exists' => 'Selected vendor does not exist.',
            'bill_no.required' => 'Please enter bill number.',
            'bill_date.required' => 'Please enter bill date.',
            'kt_docs_repeater_advanced.required' => 'Please add at least one product.',
            'kt_docs_repeater_advanced.min' => 'Please add at least one product item.',
            'kt_docs_repeater_advanced.*.id.required' => 'Material inward item ID is required.',
            'kt_docs_repeater_advanced.*.product.required' => 'Product is required.',
            'kt_docs_repeater_advanced.*.quantity.required' => 'Quantity is required.',
            'kt_docs_repeater_advanced.*.item_per_packet.required' => 'Item per packet is required.',
            'kt_docs_repeater_advanced.*.weight_per_piece.required' => 'Weight per piece is required.',
            'kt_docs_repeater_advanced.*.total_weight.required' => 'Total weight is required.',
            'kt_docs_repeater_advanced.*.rate.required' => 'Rate is required.',
            'kt_docs_repeater_advanced.*.gst.required' => 'GST is required.',
            'kt_docs_repeater_advanced.*.amount.required' => 'Amount is required.',
            'kt_docs_repeater_advanced.*.order_item_id.required' => 'Order Item is required.',
            'kt_docs_repeater_advanced.*.old_quantity.required' => 'Old quantity is required.',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $totalGst = 0;

            $materialInward = MaterialInward::findOrFail($id);
            $materialOrder = MaterialOrder::findOrFail($validated['order_id']);

            $materialInward->update([
                'received_by' => auth('admin')->id(),
                'material_order_id' => $materialOrder->id,
                'vendor_id' => $validated['vendor'],
                'bill_no' => $validated['bill_no'],
                'bill_date' => Carbon::parse($validated['bill_date'])->format('Y-m-d'),
                'status_id' => 3,
            ]);

            foreach ($validated['kt_docs_repeater_advanced'] as $item) {
                $materialOrderItem = MaterialOrderItem::findOrFail($item['order_item_id']);
                $materialInwardItem = $materialInward->items()->findOrFail($item['id']);
                $product_attribute = ProductAttribute::findOrFail($item['item_per_packet']);
                $stock = Stock::where(['id' => $item['item_per_packet'], 'product_id' => $item['product']])->first();

                if (!$stock) {
                    throw new \Exception('Stock not found for product ID ' . $item['product'] . ' and attribute ID ' . $item['item_per_packet']);
                }

                $gstAmount = ($item['rate'] * $item['quantity']) * $item['gst'] / 100;

                $materialInwardItem->update([
                    'product_id' => $item['product'],
                    'material_order_id' => $materialOrder->id,
                    'material_order_item_id' => $item['order_item_id'],
                    'product_attribute_id' => $item['item_per_packet'],
                    'quantity' => $item['quantity'],
                    'total_weight' => $item['total_weight'],
                    'rate' => $item['rate'],
                    'gst' => $item['gst'],
                    'gst_amount' => $gstAmount,
                    'amount' => $item['amount'],
                    'status_id' => 3,
                ]);

                $subtotal += $item['rate'] * $item['quantity'];
                $totalGst += $gstAmount;

                $oldStock = $stock->quantity;
                $newQuantity = $item['quantity'] * $product_attribute->item_per_packet;
                $changeStock = $newQuantity - $item['old_quantity'];
                $currentStock = $oldStock + $changeStock;

                if ($changeStock !== 0) {
                    ProductLedger::create([
                        'product_id' => $item['product'],
                        'product_attribute_id' => $item['item_per_packet'],
                        'reference_no' => $materialInward->receipt_no,
                        'type' => $changeStock > 0 ? 'in' : 'out',
                        'old_quantity' => $oldStock,
                        'new_quantity' => $changeStock,
                        'current_quantity' => $currentStock,
                        'source_type' => 'Material Inward',
                        'source_id' => $materialInward->id,
                        'note' => 'Material Inward Updated',
                        'created_by' => auth('admin')->user()->id, 
                    ]);
                }

                $stock->quantity = $currentStock;
                $stock->save();

                $materialOrderItem->status_id = 3;
                $materialOrderItem->save();
            }

            $pendingItems = MaterialOrderItem::where([
                'material_order_id' => $materialOrder->id,
                'status_id' => 1
            ])->count();

            if ($pendingItems == 0) {
                $materialOrder->status_id = 3;
            } else {
                $materialOrder->status_id = 18;
            }
            $materialOrder->save();

            $materialInward->update([
                'subtotal' => $subtotal,
                'gst_total' => $totalGst,
                'total' => $subtotal + $totalGst,
            ]);

            DB::commit();
            return response()->json([
                'class' => 'bg-success',
                'error' => false,
                'message' => 'Material Inward Updated Successfully',
                'call_back' => route('admin.material-inward.index'),
                'table_referesh' => true,
                'model_id' => 'dataSave'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'class' => 'bg-danger',
                'error' => true,
                'message' => 'Material Inward Update Failed. ' . $e->getMessage(),
                'call_back' => '',
                'table_referesh' => true,
                'model_id' => '',
                'debug' => $e->getMessage(),
            ]);
        }
    }



}

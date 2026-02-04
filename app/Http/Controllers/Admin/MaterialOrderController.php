<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\MaterialOrder\MaterialOrderCollection;
use App\Models\MaterialOrder;
use App\Models\MaterialOrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MaterialOrderController extends Controller{
    public function index(Request $request){
        
        if ($request->wantsJson()) {
            $datas = MaterialOrder::orderByRaw("CASE
                WHEN status_id = 2 THEN 1
                WHEN status_id = 5 THEN 2
                ELSE 3
            END")->orderBy('order_no', 'desc');

            $name = request()->input('name');
            if ($name) {
                $datas->where('company_name', 'like', '%'.$name.'%');
            }

            $email = request()->input('email');
            if ($email) {
                $datas->where('email', 'like', '%'.$email.'%');
            }

            $gst = request()->input('gst');
            if ($gst) {
                $datas->where('gst', 'like', '%'.$gst.'%');
            }


            $city = $request->input('city');
            if ($city) {
                $datas->where('city_id', $city);
            }

            $status = $request->input('status');
            if ($status) {
                $datas->where('status_id', $status);
            }

            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new MaterialOrderCollection($datas));
        }
        return view('admin.material-order.list'); 
    }



    public function create(){
       return view('admin.material-order.create');
    }

    

    

    public function store(Request $request){
        $validated = $request->validate([
            'vendor' => 'required|exists:vendors,id',
            'bill_to' => 'required|exists:vendors,id',
            'ship_to' => 'required|exists:vendors,id',
            'mo_date' => 'required|date',

            'kt_docs_repeater_advanced' => 'required|array|min:1',
            'kt_docs_repeater_advanced.*.product' => 'required|exists:products,id',
            'kt_docs_repeater_advanced.*.quantity' => 'required|numeric|min:1',
            'kt_docs_repeater_advanced.*.item_per_packet' => 'required|exists:product_attributes,id',
            'kt_docs_repeater_advanced.*.weight_per_piece' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.total_weight' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.rate' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.gst' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.amount' => 'required|numeric|min:0',
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
        ]);

        //dd($request->all());

        DB::beginTransaction();
       try {
            $subtotal = 0;
            $totalGst = 0;

            // 1. Save Material Order
            $materialOrder = MaterialOrder::create([
                'created_by' => auth('admin')->user()->id, 
                'vendor_id' => $validated['vendor'],
                'bill_to' => $validated['bill_to'],
                'ship_to' => $validated['ship_to'],
                'mo_date' => Carbon::parse($validated['mo_date'])->format('Y-m-d'),
                'status_id' => 1,
            ]);

            // 2. Save each item and calculate totals
            foreach ($validated['kt_docs_repeater_advanced'] as $item) {
                $gstAmount = ($item['rate'] * $item['quantity']) * $item['gst'] / 100;

                $materialOrder->items()->create([
                    'product_id' => $item['product'],
                    'product_attribute_id' => $item['item_per_packet'],
                    'quantity' => $item['quantity'],
                    'total_weight' => $item['total_weight'],
                    'rate' => $item['rate'],
                    'gst' => $item['gst'],
                    'gst_amount' => $gstAmount,
                    'amount' => $item['amount'],
                    'status_id' => 1,
                ]);

                $subtotal += $item['rate'] * $item['quantity'];
                $totalGst += $gstAmount;
            }

            // 3. Update totals
            $materialOrder->update([
                'subtotal' => $subtotal,
                'gst_total' => $totalGst,
                'total' => $subtotal + $totalGst,
            ]);

            DB::commit();
           return response()->json([
                'class' => 'bg-success',
                'error' => false,
                'message' => 'Material Order Saved Successfully',
                'call_back' => route('admin.material-order.index'),
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
        $order = MaterialOrder::with('items')->findOrFail($id);

        if ($order->status_id != 1) {
            return redirect()->back()->with([
                'class' => 'bg-danger',
                'message' => 'This Material Order cannot be edited because it is already finalized.'
            ]);
        }

        return view('admin.material-order.edit', compact('order'));
    }

    public function update(Request $request, $id){
        $validated = $request->validate([
            'vendor' => 'required|exists:vendors,id',
            'bill_to' => 'required|exists:vendors,id',
            'ship_to' => 'required|exists:vendors,id',
            'mo_date' => 'required|date',

            'kt_docs_repeater_advanced' => 'required|array|min:1',
            'kt_docs_repeater_advanced.*.product' => 'required|exists:products,id',
            'kt_docs_repeater_advanced.*.quantity' => 'required|numeric|min:1',
            'kt_docs_repeater_advanced.*.item_per_packet' => 'required|exists:product_attributes,id',
            'kt_docs_repeater_advanced.*.weight_per_piece' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.total_weight' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.rate' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.gst' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.amount' => 'required|numeric|min:0',
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
        ]);

        //dd($request->all());

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $totalGst = 0;

            $materialOrder = MaterialOrder::findOrFail($id);

            $materialOrder->update([
                'created_by' => auth('admin')->id(),
                'vendor_id' => $validated['vendor'],
                'bill_to' => $validated['bill_to'],
                'ship_to' => $validated['ship_to'],
                'mo_date' => Carbon::parse($validated['mo_date'])->format('Y-m-d'),
                'status_id' => 1,
            ]);

            $existingItemIds = $materialOrder->items()->where('status_id', 1)->pluck('id')->toArray();
            $submittedItemIds = [];

            foreach ($validated['kt_docs_repeater_advanced'] as $item) {
                $gstAmount = ($item['rate'] * $item['quantity']) * $item['gst'] / 100;

                if (!empty($item['id'])) {
                    $orderItem = $materialOrder->items()->where('status_id', 1)->find($item['id']);
                    if ($orderItem) {
                        $orderItem->update([
                            'product_id' => $item['product'],
                            'product_attribute_id' => $item['item_per_packet'],
                            'quantity' => $item['quantity'],
                            'total_weight' => $item['total_weight'],
                            'rate' => $item['rate'],
                            'gst' => $item['gst'],
                            'gst_amount' => $gstAmount,
                            'amount' => $item['amount'],
                        ]);
                        $submittedItemIds[] = $item['id'];
                    }
                } else {
                    $newItem = $materialOrder->items()->create([
                        'product_id' => $item['product'],
                        'product_attribute_id' => $item['item_per_packet'],
                        'quantity' => $item['quantity'],
                        'total_weight' => $item['total_weight'],
                        'rate' => $item['rate'],
                        'gst' => $item['gst'],
                        'gst_amount' => $gstAmount,
                        'amount' => $item['amount'],
                        'status_id' => 1,
                    ]);
                    $submittedItemIds[] = $newItem->id;
                }

                $subtotal += $item['rate'] * $item['quantity'];
                $totalGst += $gstAmount;
            }

            $itemsToDelete = array_diff($existingItemIds, $submittedItemIds);
            $materialOrder->items()->whereIn('id', $itemsToDelete)->where('status_id', 1)->delete();

            $materialOrder->update([
                'subtotal' => $subtotal,
                'gst_total' => $totalGst,
                'total' => $subtotal + $totalGst,
            ]);

            DB::commit();
            return response()->json([
                'class' => 'bg-success',
                'error' => false,
                'message' => 'Product Saved Successfully',
                'call_back' => route('admin.material-order.index'),
                'table_referesh' => true,
                'model_id' => 'dataSave'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'class' => 'bg-danger',
                'error' => true,
                'message' => 'Product Not Saved',
                'call_back' => '',
                'table_referesh' => true,
                'model_id' => '',
                'debug' => $e->getMessage()
            ]);
        }


    }


    public function show($id){
        $order = MaterialOrder::with('items')->findOrFail($id);

        return view('admin.material-order.view', compact('order'));
    }



}

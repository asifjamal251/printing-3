<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PurchaseOrderExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PurchaseOrder\PurchaseOrderCollection;
use App\Models\Foil;
use App\Models\Item;
use App\Models\ItemProcessDetail;
use App\Models\OrderSheet;
use App\Models\Processing;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseOrderController extends Controller{
    public function index(Request $request){
        if ($request->ajax()) {
            $datas = PurchaseOrder::orderByRaw("CASE
                WHEN status_id = 1 THEN 1
                WHEN status_id = 2 THEN 2
                WHEN status_id = 3 THEN 8
                WHEN status_id = 5 THEN 9
                ELSE 3
                END")->orderBy('created_at', 'desc');

            if ($request->client) {
                $datas->where('client_id', $request->client);
            }

            if ($request->po_number) {
                $datas->where('po_number', $request->po_number);
            }

            if ($request->filled('item_name')) {
                $datas->whereHas('items', function ($q) use ($request) {
                    $q->where('item_name', 'LIKE', '%'.$request->item_name.'%');
                });
            }

            if ($request->filled('item_size')) {
                $datas->whereHas('items', function ($q) use ($request) {
                    $q->where('item_size', 'LIKE', '%'.$request->item_size.'%');
                });
            }

            if ($request->filled('po_date')) {
                $dateRange = str_replace(' to ', ' - ', $request->po_date);
                $dates = array_map('trim', explode(' - ', $dateRange));

                try {
                    $from = Carbon::parse($dates[0])->startOfDay();

                    $to = isset($dates[1]) && $dates[1] !== ''
                    ? Carbon::parse($dates[1])->endOfDay()
                    : $from->copy()->endOfDay();

                    $datas->whereBetween('po_date', [$from, $to]);

                } catch (\Exception $e) {
                    // Invalid date → ignore filter safely
                }
            }

            $totaldata = $datas->count();
            $request->merge(['recordsTotal' => $totaldata, 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();
            return response()->json(new PurchaseOrderCollection($datas));
        }
        return view('admin.purchase-order.list');
    }

    public function create(){
        return view('admin.purchase-order.create');
    }


    public function edit($id){
        $purchase_order = PurchaseOrder::with('items')->findOrFail($id);

        $hasActiveItem = $purchase_order->items
            ->where('status_id', 1)
            ->isNotEmpty();

        if (! $hasActiveItem) {
            return redirect()
                ->route('admin.purchase-order.index')
                ->with([ 'class' => 'bg-danger', 'message'=> 'You cannot edit this purchase order. No active items available.']);
        }

        return view('admin.purchase-order.edit', compact('purchase_order'));
    }


    

    public function addMoreItem(Request $request, $id){
        DB::transaction(function () use ($request, $id) {
            $po = PurchaseOrder::findOrFail($id);
            $poItems = session('po_item', []);
            foreach ($poItems as $poItem) {
                $item = Item::find($poItem['po_item_id']);
                if (!$item) {
                    continue; 
                }

                $quantity      = $poItem['quantity'] ?? 0;
                $rate          = $poItem['rate'] ?? 0;
                $gstPercentage = $poItem['gst_percentage'] ?? 18;
                $remarks       = $poItem['remarks'] ?? null;
                $batch         = $poItem['batch'] ?? null;

                $amount      = $quantity * $rate;
                $gstAmount   = ($amount * $gstPercentage) / 100;
                $totalAmount = $amount + $gstAmount;

                $poItemRecord = PurchaseOrderItem::updateOrCreate(
                    [
                        'purchase_order_id' => $po->id,
                        'item_id'           => $item->id,
                    ],
                    [
                        'product_type_id'        => $item->product_type_id,
                        'item_name'              => $item->item_name,
                        'item_size'              => $item->item_size,
                        'colour'                 => $item->colour,
                        'gsm'                    => $item->gsm,
                        'coating_type_id'        => $item->coating_type_id,
                        'other_coating_type_id'  => $item->other_coating_type_id,
                        'embossing'              => $item->embossing,
                        'leafing'                => $item->leafing,
                        'back_print'             => $item->back_print,
                        'braille'                => $item->braille,
                        'artwork_code'           => $item->artwork_code,
                        'quantity'               => $quantity,
                        'rate'                   => $rate,
                        'gst_percentage'         => $gstPercentage,
                        'amount'                 => $amount,
                        'gst_amount'             => $gstAmount,
                        'batch'                  => $batch,
                        'total_amount'           => $totalAmount,
                        'remarks'                => $remarks,
                        'status_id'              => 1,
                    ]
                );


                ItemProcessDetail::updateOrCreate(
                    [
                        'purchase_order_id'      => $po->id,
                        'purchase_order_item_id' => $poItemRecord->id,
                        'item_id'                => $item->id,
                    ],
                    [
                        'product_type_id'        => $item->product_type_id ?? null,
                        'dye_id'                 => $item->lastItem->dey_id ?? null,
                        'job_card_id'            => null,
                        'product_id'             => $item->lastItem->product_id ?? null,
                        'batch'                  => $batch,
                        'colour'                 => $item->colour ?? null,
                        'gsm'                    => $item->gsm ?? null,
                        'coating_type_id'        => $item->coating_type_id ?? null,
                        'other_coating_type_id'  => $item->other_coating_type_id ?? null,
                        'embossing'              => $item->embossing ?? null,
                        'leafing'                => $item->leafing ?? null,
                        'back_print'             => $item->back_print ?? null,
                        'braille'                => $item->braille ?? null,
                        'artwork_code'           => $item->artwork_code ?? null,
                        'job_type'               => $item->lastItem->job_type ?? null,
                        'printing_machine'       => $item->lastItem->printing_machine ?? null,
                        'sheet_size'             => $item->lastItem->sheet_size ?? null,
                        'number_of_sheet'        => $item->lastItem->number_of_sheet ?? null,
                        'set_number'             => $item->lastItem->set_number ?? null,
                        'ups'                    => $item->lastItem->ups ?? null,
                        'quantity'               => $quantity,
                        'rate'                   => $rate,
                        'gst_percentage'         => $gstPercentage,
                        'amount'                 => $amount,
                        'gst_amount'             => $gstAmount,
                        'total_amount'           => $totalAmount,
                        'status_id'              => 1,
                    ]
                );

            }

            session()->forget('po_item');
        });

   return redirect()->route('admin.purchase-order.index')->with('success', 'Purchase Order Updated Successfully.');
}

public function show($id){

    $purchase_order = PurchaseOrder::findOrFail($id);
    return view('admin.purchase-order.view', compact('purchase_order'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'client'  => 'required|exists:clients,id',
        'po_number' => [
            'required',
            'string',
            'max:255',
            Rule::unique('purchase_orders')
                ->ignore($id)
                ->where(fn($query) => $query->where('client_id', $request->client)),
        ],
        'po_date'  => 'required|date',
        'remarks'  => 'nullable|string|max:255',

        'kt_docs_repeater_advanced' => 'required|array|min:1',
        'kt_docs_repeater_advanced.*.item' => 'required|exists:purchase_order_items,id',
        'kt_docs_repeater_advanced.*.quantity' => 'required|numeric|min:0',
        'kt_docs_repeater_advanced.*.rate' => 'required|numeric|min:0',
        'kt_docs_repeater_advanced.*.gst_percentage' => 'required|numeric|min:0|max:100',
        'kt_docs_repeater_advanced.*.remarks' => 'nullable|string|max:1000',
    ]);

    DB::beginTransaction();

    try {
        $po = PurchaseOrder::findOrFail($id);

        $po->update([
            'client_id'   => $request->client,
            'po_number'   => $request->po_number,
            'po_date'     => Carbon::parse($request->po_date)->format('Y-m-d'),
            'remarks'     => $request->remarks,
            'added_by'    => auth('admin')->id(),
        ]);

        // $requestItemIds = collect($request->kt_docs_repeater_advanced)
        //     ->pluck('item')
        //     ->filter(fn($v) => !empty($v))
        //     ->unique()
        //     ->values()
        //     ->toArray();

        // if (!empty($requestItemIds)) {
        //     PurchaseOrderItem::where('purchase_order_id', $po->id)
        //         ->where('status_id', 1)
        //         ->whereNotIn('id', $requestItemIds)
        //         ->delete();

        //     ItemProcessDetail::where('purchase_order_id', $po->id)
        //         ->where('status_id', 1)
        //         ->whereNotIn('purchase_order_item_id', $requestItemIds)
        //         ->delete();
        // }

        foreach ($request->kt_docs_repeater_advanced as $po_item) {

            $purchase_order_item = PurchaseOrderItem::where('id', $po_item['item'])
                ->where('purchase_order_id', $po->id)
                ->where('status_id', 1)
                ->first();

            if (!$purchase_order_item) {
                continue;
            }

            $item = Item::findOrFail($purchase_order_item->item_id);

            $rate          = $po_item['rate'] ?? 0;
            $gstPercentage = $po_item['gst_percentage'] ?? 18;
            $quantity      = $po_item['quantity'] ?? 0;

            $amount      = $quantity * $rate;
            $gstAmount   = ($amount * $gstPercentage) / 100;
            $totalAmount = $amount + $gstAmount;

            $purchase_order_item->update([
                'item_name'       => $item->item_name,
                'item_size'       => $item->item_size,
                'colour'          => $item->colour,
                'gsm'             => $item->gsm,
                'coating_type_id' => $item->coating_type_id,
                'other_coating_type_id'=> $item->other_coating_type_id,
                'embossing'       => $item->embossing,
                'leafing'         => $item->leafing,
                'back_print'      => $item->back_print,
                'braille'         => $item->braille,
                'artwork_code'    => $item->artwork_code,
                'quantity'        => $quantity,
                'rate'            => $rate,
                'gst_percentage'  => $gstPercentage,
                'remarks'         => $po_item['remarks'] ?? null,
            ]);

            ItemProcessDetail::updateOrCreate(
                [
                    'purchase_order_id' => $po->id,
                    'purchase_order_item_id' => $purchase_order_item->id,
                    'item_id' => $purchase_order_item->item_id,
                ],
                [
                    'quantity'              => $quantity,
                    'rate'                  => $rate,
                    'gst_percentage'        => $gstPercentage,
                    'amount'                => $amount,
                    'gst_amount'            => $gstAmount,
                    'total_amount'          => $totalAmount,
                    'colour'                => $item->colour ?? null,
                    'gsm'                   => $item->gsm ?? null,
                    'coating_type_id'       => $item->coating_type_id ?? null,
                    'other_coating_type_id' => $item->other_coating_type_id ?? null,
                    'embossing'             => $item->embossing ?? null,
                    'leafing'               => $item->leafing ?? null,
                    'back_print'            => $item->back_print ?? null,
                    'braille'               => $item->braille ?? null,
                    'product_id'            => $item->lastItem->product_id ?? null,
                ]
            );
        }

        DB::commit();

        return response()->json([
            'class' => 'bg-success',
            'error' => false,
            'message' => 'Purchase Order Updated Successfully',
            'call_back' => route('admin.purchase-order.index'),
            'table_referesh' => false,
        ]);
    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'class' => 'bg-danger',
            'error' => true,
            'message' => 'Purchase Order Update Failed. ' . $e->getMessage(),
            'call_back' => '',
            'table_referesh' => true,
            'model_id' => '',
        ]);
    }
}

// public function update(Request $request, $id)
// {
//     $request->validate([
//         'client'  => 'required|exists:clients,id',
//         'po_number' => [
//             'required',
//             'string',
//             'max:255',
//             Rule::unique('purchase_orders')
//             ->ignore($id) 
//             ->where(fn($query) =>
//                 $query->where('client_id', $request->client)
//             ),
//         ],
//         'po_date'  => 'required|date',
//         'remarks'  => 'nullable|string|max:255',

//         'kt_docs_repeater_advanced' => 'required|array|min:1',
//         'kt_docs_repeater_advanced.*.rate' => 'required|numeric|min:0',
//         'kt_docs_repeater_advanced.*.gst_percentage' => 'required|numeric|min:0|max:100',
//         'kt_docs_repeater_advanced.*.remarks' => 'nullable|string|max:1000',
//     ], [
//         'kt_docs_repeater_advanced.*.rate.required' => 'Rate is required.',
//         'kt_docs_repeater_advanced.*.gst_percentage.required' => 'GST percentage is required.',
//     ]);

//     DB::beginTransaction();

//     try {
//         $po = PurchaseOrder::findOrFail($id);

//         $po->update([
//             'client_id'   => $request->client,
//             'po_number' => $request->po_number,
//             'po_date'   => Carbon::parse($request->po_date)->format('Y-m-d'),
//             'remarks'   => $request->remarks,
//             'added_by'  => auth('admin')->id()
//         ]);


//         $requestItemIds = collect($request->kt_docs_repeater_advanced)
//         ->pluck('item')
//         ->filter()
//         ->toArray();

//         PurchaseOrderItem::where('purchase_order_id', $po->id)
//         ->whereNotIn('id', $requestItemIds)
//         ->delete();

//         ItemProcessDetail::where('purchase_order_id', $po->id)
//         ->whereDoesntHave('purchaseOrderItem', function ($q) use ($requestItemIds) {
//             $q->whereIn('id', $requestItemIds);
//         })
//         ->delete();

//         foreach ($request->kt_docs_repeater_advanced as $po_item) {
//             $purchase_order_item = PurchaseOrderItem::findOrFail($po_item['item']);
//             $item = Item::findOrFail($purchase_order_item->item_id);

//             $rate        = $po_item['rate'] ?? 0;
//             $gstPercentage = $po_item['gst_percentage'] ?? 18;
//             $quantity    = $po_item['quantity'] ?? 0;
//             $amount      = $quantity * $rate;
//             $gstAmount   = ($amount * $gstPercentage) / 100;
//             $totalAmount = $amount + $gstAmount;

//             if ($purchase_order_item) {
//                 $purchase_order_item->update([
//                     'item_name'         => $item->item_name,
//                     'item_size'         => $item->item_size,
//                     'colour'            => $item->colour,
//                     'gsm'               => $item->gsm,
//                     'coating'           => $item->coating,
//                     'other_coating'     => $item->other_coating,
//                     'embossing'         => $item->embossing,
//                     'leafing'           => $item->leafing,
//                     'back_print'        => $item->back_print,
//                     'braille'           => $item->braille,
//                     'artwork_code'      => $item->artwork_code,
//                     'quantity' => $po_item['quantity'] ?? 0,
//                     'rate' => $po_item['rate'] ?? 0,
//                     'gst_percentage' => $po_item['gst_percentage'] ?? 18,
//                     'remarks' => $po_item['remarks'] ?? null,
//                 ]);

//                 ItemProcessDetail::updateOrCreate([
//                     'item_id' => $purchase_order_item->item_id,
//                     'purchase_order_id' => $po->id,
//                     'purchase_order_item_id' => $purchase_order_item ->id,
//                 ],
//                 [

//                     'quantity'          => $po_item['quantity'],
//                     'rate'              => $po_item['rate'],
//                     'gst_percentage'    => $po_item['gst_percentage'],
//                     'amount'            => $amount,
//                     'gst_amount'        => $gstAmount,
//                     'total_amount'      => $totalAmount,

//                     'colour'                  => $item->colour ?? null,
//                     'gsm'                     => $item->gsm ?? null,
//                     'coating_type_id'         => $item->coating_type_id ?? null,
//                     'other_coating_type_id'   => $item->other_coating_type_id ?? null,
//                     'embossing'               => $item->embossing ?? null,
//                     'leafing'                 => $item->leafing ?? null,
//                     'back_print'              => $item->back_print ?? null,
//                     'braille'                 => $item->braille ?? null,
//                     'product_id'              => $item->lastItem->product_id ?? null,
//                 ]);
//             }
//         }

//         DB::commit();

//         return response()->json([
//             'class' => 'bg-success',
//             'error' => false,
//             'message' => 'Purchase Order Updated Successfully',
//             'call_back' => route('admin.purchase-order.index'),
//             'table_referesh' => false,
//         ]);
//     } catch (\Exception $e) {
//         DB::rollBack();
//         return response()->json([
//             'class' => 'bg-danger',
//             'error' => true,
//             'message' => 'Purchase Order Update Failed. ' . $e->getMessage(),
//             'call_back' => '',
//             'table_referesh' => true,
//             'model_id' => '',
//         ]);
//     }
// }


public function approval(Request $request, $id){
    $purchase_order = PurchaseOrder::where('id', $id)->firstOrFail();

    if ($request->ajax()) {
        $datas = PurchaseOrderItem::where('purchase_order_id', $id);

        $totaldata = $datas->count();
        $result["length"]= $request->length;
        $result["recordsTotal"]= $totaldata;
        $result["recordsFiltered"]= $datas->count();
        $totaldata = $datas->count();

        $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
        $records = $datas->limit($request->length)->offset($request->start)->get();

        $result['data'] = [];
        foreach ($records as $item) {

            $result['data'][] = [
                'sn' => ++$request->start,
                'id' => $item->id,
                'last_date' => $item->item?->lastItem?->created_at?->format('d F Y')??'--',
                'mfg_mkdt_by' => '<div class="col"><p class="mt-0 mb-0">'.$item?->item->mfgBy?->company_name.'</p><p class="text-muted mt-0 mb-0">'.$item?->item->mkdtBy?->company_name.'</p></div>',
                'item_name' => $item->item_name,
                'item_size' => $item->item_size??'',
                'coating' => $item->coatingType?->name.'<br>'.'<span class="text-danger">'.$item->otherCoatingType?->name.'</span>',
                'colour' => $item->colour??'',

                'remarks' => $item->remarks,
                'coa' => '<a href="'.route('admin.purchase-order.show.coa', $item->id).'">View COA</a>',
                'status' => status($item->status_id),
                'status_id' => $item->status_id,
                'checkbox' => $item->status_id == 1
                ? '<div class="form-check form-check-outline form-check-success mb-0">
                <input class="form-check-input assignProcess" type="checkbox" value="' . $item->id . '" id="checkbox_' . $item->id . '">
                <label class="form-check-label" for="checkbox_' . $item->id . '"></label>
                </div>'
                : '<div class="form-check form-check-outline form-check-warning mb-0">
                <input class="form-check-input" type="checkbox" checked="checked" disabled>
                <label class="form-check-label"></label>
                </div>',


                'quantity' => $item->quantity,


                'rate' => $item->rate,

            ];
        }

        return $result;
    }
    return view('admin.purchase-order.approval', compact('purchase_order'));
}

public function showCoa(Request $request, $id){
    $coa = PurchaseOrderItem::findOrFail($id);
    return view('admin.purchase-order.coa', compact('coa'));
}

public function updateItem(Request $request, $id){
    $item = PurchaseOrderItem::findOrFail($id);

    $item->update([
        'quantity' => $request->quantity,
        'rate' => $request->rate,
        'status_id' => $request->status,
        'remarks' => $request->remarks,
    ]);

    return response()->json([
        'class' => 'bg-success',
        'error' => false,
        'message' => 'Purchase Order Item Updated Successfully',
        'call_back' => '',
        'table_referesh' => true,
        'model_id' => 'dataSave'
    ]);
}

public function editItem($id){
    $item = PurchaseOrderItem::findOrFail($id);
    return view('admin.purchase-order.item-edit', compact('item'));
}


public function destroy($id){
    DB::beginTransaction();

    try {
        $po = PurchaseOrder::with('items')->findOrFail($id);
        ItemProcessDetail::where('purchase_order_id', $po->id)->delete();
        $po->update([
            'status_id' => 5
        ]);

        PurchaseOrderItem::where('purchase_order_id', $po->id)
        ->update([
            'status_id' => 5
        ]);

        DB::commit();

        return response()->json([
            'error' => false,
            'class' => 'bg-success',
            'message' => 'Purchase Order cancelled successfully',
            'call_back' => route('admin.purchase-order.index'),
            'table_referesh' => true,
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'error' => true,
            'class' => 'bg-danger',
            'message' => 'Cancel failed: ' . $e->getMessage(),
            'table_referesh' => false,
        ]);
    }
}



public function assignOrderSheet(Request $request){
    $ids = $request->input('ids', []);

    if (empty($ids)) {
        return response()->json([
            'class'   => 'bg-warning',
            'error'   => true,
            'message' => 'No items selected.'
        ], 422);
    }

    try {
        DB::transaction(function () use ($ids) {
            $items = PurchaseOrderItem::whereIn('id', $ids)->get();

            foreach ($items as $item) {
                OrderSheet::updateOrCreate(
                    [
                        'purchase_order_item_id' => $item->id,
                        'item_id'                => $item->item_id,
                        'item_process_details_id'=> $item->itemProcessDetail->id,
                    ],
                    [
                        'purchase_order_id' => $item->purchase_order_id,
                        'status_id'         => 1,
                    ]
                );

                $item->update(['status_id' => 21]);
                PurchaseOrder::where('id', $item->purchase_order_id)->update(['status_id' => 2]);
                    // $allAssigned = PurchaseOrderItem::where('purchase_order_id', $item->purchase_order_id)
                    //     ->where('status_id', '!=', 21)
                    //     ->doesntExist();

                    // if ($allAssigned) {
                    //     PurchaseOrder::where('id', $item->purchase_order_id)->update(['status_id' => 2]);
                    // }
            }
        });

        return response()->json([
            'class'          => 'bg-success',
            'error'          => false,
            'message'        => 'Processing assigned successfully.',
            'table_referesh' => true,
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'class'   => 'bg-danger',
            'error'   => true,
            'message' => 'Something went wrong! ' . $e->getMessage(),
        ], 500);
    }
}




public function cancelItem(Request $request)
{
    DB::transaction(function () use ($request, &$response) {

        $item = PurchaseOrderItem::lockForUpdate()
            ->where('id', $request->id)
            ->where('status_id', 1)
            ->first();

        if (! $item) {
            $response = response()->json([
                'message' => 'Item not found or already processed.',
                'title'   => 'PO Item Cancel',
                'class'   => 'bg-danger'
            ]);
            return;
        }

        $item->update(['status_id' => 5]);

        $poId = $item->purchase_order_id;

        $pending = PurchaseOrderItem::where('purchase_order_id', $poId)
            ->where('status_id', '!=', 5)
            ->exists();

        if (! $pending) {
            PurchaseOrder::where('id', $poId)->update(['status_id' => 5]);
        }

        $response = response()->json([
            'message' => 'Item cancelled successfully.',
            'title'   => 'PO Item Cancel',
            'class'   => 'bg-success'
        ]);
    });

    return $response;
}




public function destroyItem(Request $request, $id)
{
    DB::transaction(function () use ($id, &$response) {

        $item = PurchaseOrderItem::lockForUpdate()
            ->where('id', $id)
            ->where('status_id', 1)
            ->first();

        if (! $item) {
            $response = response()->json([
                'class'   => 'bg-danger',
                'message' => 'Item not found.',
            ]);
            return;
        }

        $poId = $item->purchase_order_id;

        $item->delete();

        $hasItems = PurchaseOrderItem::where('purchase_order_id', $poId)->exists();

        if (! $hasItems) {
            PurchaseOrder::where('id', $poId)->delete();
        }

        $response = response()->json([
            'class'   => 'bg-success',
            'message' => 'Item deleted successfully.',
        ]);
    });

    return $response;
}




public function exportForm(){
    return view('admin.purchase-order.export');
}


public function export(Request $request){
    $filters = $request->only([
        'export_status',
        'export_po_date',
        'export_clients',
    ]);

    $filename = 'purchase_order_' . now()->format('d-m-Y') . '.xlsx';

    Excel::store(
        new PurchaseOrderExport($filters),
        'excel/' . $filename,
        'public'
    );

    return response()->json([
        'message'  => 'Purchase Order exported successfully.',
        'filename' => asset('storage/excel/' . $filename),
        'class'    => 'success'
    ]);
}

}

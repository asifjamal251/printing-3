<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Item\ItemCollection;
use App\Models\Item;
use App\Models\ItemProcessDetail;
use App\Models\ItemType;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use App\Imports\ItemImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {

            $poItem = session('po_item');

            $datas = Item::with(['productType', 'mkdtBy', 'mfgBy']);

            if (!empty($poItem)) {
                $poItemIds = array_column($poItem, 'po_item_id');
                if (!empty($poItemIds)) {
                    $ids = implode(',', $poItemIds);
                    $datas->orderByRaw("FIELD(id, $ids) DESC");
                }
            } else {
                $datas->orderBy('created_at', 'desc');
            }

            if ($request->mkdt_by) {
                $datas->where('mkdt_by', $request->mkdt_by);
            }

            if ($request->mfg_by) {
                $datas->where('mfg_by', $request->mfg_by);
            }

            if ($request->client) {
                $datas->where('mfg_by', $request->client);
            }

            if ($request->item_name) {
                $search = strtolower(trim($request->item_name));

                $datas->whereRaw(
                    'LOWER(item_name) LIKE ?',
                    ['%' . $search . '%']
                );
            }

            if ($request->item_size) {
                $datas->where('item_size', 'like', "%{$request->item_size}%");
            }

            if ($request->set_no) {
                $datas->whereHas('itemProcessDetails', function ($q) use ($request) {
                    $q->where('set_number', 'like', "%{$request->set_no}%");
                });
            }

            $totaldata = $datas->count();
            $request->merge(['recordsTotal' => $totaldata, 'length' => $request->length]);

            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new ItemCollection($datas));

        }

        return view('admin.item.list');
    }


    public function create(){
        return view('admin.item.create');
    }




    public function store(Request $request){
        $rules = [
            'product_type'    => 'required|exists:product_types,id',
            'item_name'       => 'required|string|max:191',
            'item_size'       => 'required|string|max:191',
            'colour'          => 'required|string|max:191',
            'gsm'             => 'required|string|max:191',
            'coating'         => 'required',
            'other_coating'   => 'required',
            'embossing'       => 'required|in:Yes,No',
            'leafing'         => 'required|in:Yes,No',
            'back_print'      => 'required|in:Yes,No',
            'braille'         => 'required|in:Yes,No',
            'artwork_code'    => 'nullable|string|max:191',
            'status'          => 'required|exists:statuses,id',
        ];

        if (auth('admin')->user()?->hasAccess('mfg_mkdt_item')) {
            $rules['mfg_by']  = 'required|exists:clients,id';
            $rules['mkdt_by'] = 'required|exists:clients,id';
        } else {
            $rules['client'] = 'required|exists:clients,id';
        }

        $validated = $request->validate($rules);


        try {
            DB::transaction(function () use ($request) {

                $mfgBy  = $request->mfg_by ?? null;
                $mkdtBy = $request->mkdt_by ?? null;

                if ($request->product_type == 'client') {
                    $mfgBy  = $request->client;
                    $mkdtBy = $request->client;
                }

                Item::create([
                    'mkdt_by'         => $mkdtBy,
                    'mfg_by'          => $mfgBy,
                    'product_type_id' => $request->product_type,
                    'item_name'       => $request->item_name,
                    'item_size'       => $request->item_size,
                    'colour'          => $request->colour,
                    'gsm'             => $request->gsm,
                    'coating_type_id'      => $request->coating,
                    'other_coating_type_id'=> $request->other_coating,
                    'embossing'       => $request->embossing,
                    'leafing'         => $request->leafing,
                    'back_print'      => $request->back_print,
                    'braille'         => $request->braille,
                    'artwork_code'    => $request->artwork_code,
                    'status_id'       => $request->status,
                ]);
            });

            return response()->json([
                'class'          => 'bg-success',
                'error'          => false,
                'message'        => 'Item created successfully',
                'table_refresh'  => true,
                'call_back' => '',
                'model_id'       => 'dataSave',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create item: ' . $e->getMessage(),
                'class'   => 'error',
            ], 500);
        }
    }

    public function edit($id)
    {
        $item = Item::find($id);
        return view('admin.item.edit', compact('item'));
    }


    public function show($id)
    {
        $item = Item::find($id);
        return view('admin.item.view', compact('item'));
    }


    
    public function update(Request $request, $id){
        $rules = [
            'product_type'    => 'required|exists:product_types,id',
            'item_name'       => 'required|string|max:191',
            'item_size'       => 'required|string|max:191',
            'colour'          => 'required|string|max:191',
            'gsm'             => 'required|string|max:191',
            'coating'         => 'required',
            'other_coating'   => 'required',
            'embossing'       => 'required|in:Yes,No',
            'leafing'         => 'required|in:Yes,No',
            'back_print'      => 'required|in:Yes,No',
            'braille'         => 'required|in:Yes,No',
            'artwork_code'    => 'nullable|string|max:191',
            'status'          => 'required|exists:statuses,id',
        ];

        if (auth('admin')->user()?->hasAccess('mfg_mkdt_item')) {
            $rules['mfg_by']  = 'required|exists:clients,id';
            $rules['mkdt_by'] = 'required|exists:clients,id';
        } else {
            $rules['client'] = 'required|exists:clients,id';
        }

        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $id) {
                $item = Item::findOrFail($id);

                $mfgBy  = $request->mfg_by ?? null;
                $mkdtBy = $request->mkdt_by ?? null;

                // if (auth('admin')->user()?->hasAccess('mfg_mkdt_item')) { 
                //     $mfgBy  = $request->mfg_by ?? null;
                //     $mkdtBy = $request->mkdt_by ?? null;
                // } else{
                //     $mfgBy  = $request->client;
                //     $mkdtBy = $request->client;
                // }
                $item->update([
                    'mkdt_by'         => $request->mkdt_by,
                    'mfg_by'          => $request->mfg_by,
                    'product_type_id' => $request->product_type,
                    'item_name'       => $request->item_name,
                    'item_size'       => $request->item_size,
                    'colour'          => $request->colour,
                    'gsm'             => $request->gsm,
                    'coating_type_id'      => $request->coating,
                    'other_coating_type_id'=> $request->other_coating,
                    'embossing'       => $request->embossing,
                    'leafing'         => $request->leafing,
                    'back_print'      => $request->back_print,
                    'braille'         => $request->braille,
                    'artwork_code'    => $request->artwork_code,
                    'status_id'       => $request->status,
                ]);
            });

            return response()->json([
                'class'          => 'bg-success',
                'error'          => false,
                'message'        => 'Item updated successfully',
                'table_refresh'  => true,
                'call_back' => '',
                'model_id'       => 'dataSave',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update item: ' . $e->getMessage(),
                'class'   => 'error',
            ], 500);
        }
    }

    public function addToPO(Request $request, $id){
        $item = Item::findOrFail($id);
        return view('admin.item.add-to-po', compact('item'));
    }

    


    public function addToPOStore(Request $request, $id){
        $request->validate([
            'quantity'       => 'required|numeric|min:1',
            'rate'           => 'required|numeric|min:0',
            'gst_percentage' => 'required|numeric|min:0|max:100',
            'remarks'        => 'nullable|string|max:255',
        ]);

        $item = Item::findOrFail($id);
        $poItem = session()->get('po_item', []);

        if (isset($poItem[$id])) {
            unset($poItem[$id]);
            session()->put('po_item', $poItem);

            return response()->json([
                'class'         => 'bg-warning',
                'error'         => false,
                'message'       => 'Item removed from PO successfully',
                'table_refresh' => true,
                'call_back'     => '',
                'model_id'      => 'dataSave',
                'removed'       => true,
            ]);
        }

        // if (!empty($poItem)) {
        //     $firstItemId = array_key_first($poItem);
        //     $firstItem   = Item::find($firstItemId);

        //     if ($firstItem) {
        //         if ($firstItem->mfg_by != $item->mfg_by || $firstItem->mkdt_by != $item->mkdt_by) {
        //             return response()->json([
        //                 'class'         => 'bg-danger',
        //                 'error'         => true,
        //                 'message'       => 'All items in a PO must have the same MFG BY and MKDT BY.',
        //                 'table_refresh' => false,
        //                 'call_back'     => '',
        //                 'model_id'      => 'dataSave',
        //                 'removed'       => false,
        //             ], 422);
        //         }
        //     }
        // }

        $poItem[$id] = [
            "po_item_id"     => $item->id,
            "user_id"        => auth('admin')->id(),
            "quantity"       => $request->quantity,
            "rate"           => $request->rate,
            "gst_percentage" => $request->gst_percentage,
            "batch"          => $request->batch,
            "remarks"        => $request->remarks,
        ];

        session()->put('po_item', $poItem);

        return response()->json([
            'class'         => 'bg-success',
            'error'         => false,
            'message'       => 'Item added/updated in PO successfully',
            'table_refresh' => true,
            'call_back'     => '',
            'model_id'      => 'dataSave',
            'removed'       => false,
        ]);
    }


    public function generatePO(){
        return view('admin.item.generate-po');
    }


    public function storePO(Request $request){
        $request->validate([
            'client' => 'required|exists:clients,id',
            'po_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('purchase_orders')
                    ->where(fn($query) =>
                        $query->where('client_id', $request->client)
                    ),
            ],
            'po_date'  => 'required|date',
            'remarks'  => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $po = PurchaseOrder::create([
                    'client_id'   => $request->client,
                    'po_number' => $request->po_number,
                    'po_date'   => Carbon::parse($request->po_date)->format('Y-m-d'),
                    'remarks'   => $request->remarks,
                    'added_by'  => auth('admin')->id(),
                    'status_id' => 1,
                ]);

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

                    $poItemRecord = PurchaseOrderItem::create([
                        'purchase_order_id' => $po->id,
                        'item_id'           => $item->id,
                        'product_type_id'   => $item->product_type_id,
                        'item_name'         => $item->item_name,
                        'item_size'         => $item->item_size,
                        'colour'            => $item->colour,
                        'gsm'               => $item->gsm,
                        'coating_type_id'        => $item->coating_type_id,
                        'other_coating_type_id'  => $item->other_coating_type_id,
                        'embossing'         => $item->embossing,
                        'leafing'           => $item->leafing,
                        'back_print'        => $item->back_print,
                        'braille'           => $item->braille,
                        'artwork_code'      => $item->artwork_code,
                        'quantity'          => $quantity,
                        'rate'              => $rate,
                        'gst_percentage'    => $gstPercentage,
                        'amount'            => $amount,
                        'gst_amount'        => $gstAmount,
                        'batch'             => $batch,
                        'total_amount'      => $totalAmount,
                        'remarks'           => $remarks,
                        'status_id'         => 1,
                    ]);


                    ItemProcessDetail::create([
                        'item_id'                 => $item->id,
                        'purchase_order_id'       => $po->id,
                        'purchase_order_item_id'  => $poItemRecord->id,
                        'product_type_id'         => $item->product_type_id ?? null,

                        'dye_id'                  => $item->lastItem->dey_id ?? null,
                        'job_card_id'             => null,
                        'product_id'              => $item->lastItem->product_id ?? null,

                        'batch'                   => $batch,

                        'colour'                  => $item->colour ?? null,
                        'gsm'                     => $item->gsm ?? null,
                        'coating_type_id'              => $item->coating_type_id ?? null,
                        'other_coating_type_id'        => $item->other_coating_type_id ?? null,
                        'embossing'               => $item->embossing ?? null,
                        'leafing'                 => $item->leafing ?? null,
                        'back_print'              => $item->back_print ?? null,
                        'braille'                 => $item->braille ?? null,
                        'artwork_code'            => $item->artwork_code ?? null,

                        'job_type'                => $item->lastItem->job_type ?? null,
                        'printing_machine'        => $item->lastItem->printing_machine ?? null,

                        'sheet_size'              => $item->lastItem->sheet_size ?? null,
                        'number_of_sheet'         => $item->lastItem->number_of_sheet ?? null,
                        'set_number'              => $item->lastItem->set_number ?? null,
                        'ups'                     => $item->lastItem->ups ?? null,

                        'quantity'                => $quantity,
                        'rate'                    => $rate,
                        'gst_percentage'          => $gstPercentage,
                        'amount'                  => $amount,
                        'gst_amount'              => $gstAmount,
                        'total_amount'            => $totalAmount,
                        'status_id'               => 1,
                    ]);

                }

                session()->forget('po_item');
            });

            return response()->json([
                'class'         => 'bg-success',
                'error'         => false,
                'message'       => 'Purchase Order created successfully!',
                'table_refresh' => true,
                'call_back'     => route('admin.purchase-order.index'),
                'model_id'      => 'dataSave',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create Purchase Order: ' . $e->getMessage(),
                'class'   => 'bg-danger',
                'error'   => true,
            ], 500);
        }
    }






    public function importCreate(){
        return view('admin.item.import'); // create a simple blade form with file input
    }

    public function importStore(Request $request){
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        $path = $request->file('file')->store('temp');

        $import = new ItemImport();

        try {
            Excel::import($import, $path, null, \Maatwebsite\Excel\Excel::XLSX);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // Excel package-level validation exceptions
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors()
                ];
            }
            return response()->json(['status' => 'error', 'errors' => $errors], 422);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        } finally {
            Storage::delete($path);
        }
        

        // combine errors captured inside import class
        if (!empty($import->errors)) {
            return response()->json([
                'class'          => 'bg-warning',
                'error'          => true,
                'message'        => 'Item import with error successfully',
                'table_refresh'  => true,
                'call_back' => '',
                'model_id'       => 'dataSave',
                'errors' => $import->errors,
            ]);
            return response()->json([
                'status' => 'partial',
                'message' => 'Import finished with some row-level errors.',
                'errors' => $import->errors
            ], 207);
        }

        return response()->json([
            'class'          => 'bg-success',
            'error'          => false,
            'message'        => 'Item import successfully',
            'table_refresh'  => true,
            'call_back' => '',
            'model_id'       => 'dataSave',
        ]);

        //return response()->json(['status' => 'success', 'message' => 'Import completed successfully.']);
    }



}

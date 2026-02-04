<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\MaterialIssue\MaterialIssueCollection;
use App\Mail\SaleInvoiceMail;
use App\Models\Client;
use App\Models\ClientRate;
use App\Models\DailyReport;
use App\Models\Department;
use App\Models\MaterialIssue;
use App\Models\MaterialIssueItem;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductLedger;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;


class MaterialIssueController extends Controller{
    public function index(Request $request){
        if ($request->ajax()) {
            if ($request->type === 'department') {
                $categories = Department::orderBy('name', 'asc')->get();
                $cat = array();
                foreach ($categories as $cat2) {
                    $cat[] = ['id' => $cat2->id, 'text' => $cat2->name, 'a_attr' => ['href' => route('admin.material-issue.index', 'department=' . $cat2->id)], 'parent' => ($cat2->parent) ? $cat2->parent : '#'];
                }
                return response()->json($cat);
            }

            $datas = MaterialIssue::orderByRaw("CASE
                WHEN status_id = 1 THEN 1
                WHEN status_id = 3 THEN 2
                ELSE 3
                END")->orderBy('id', 'desc');


            $search = $request->input('search');
            if ($search) {
                $datas->where(function ($q) use ($search) {
                    $q->where('order_no', 'like', '%' . $search . '%')
                    ->orWhere('invoice_no', 'like', '%' . $search . '%')
                    ->orWhere('dispatch_through', 'like', '%' . $search . '%')
                    ->orWhere('vehicle_no', 'like', '%' . $search . '%');
                });
            }

            $ship_to = $request->input('ship_to');
            if ($ship_to) {
                $datas->where('ship_to', $ship_to);
            }

            $bill_to = $request->input('bill_to');
            if ($bill_to) {
                $datas->where('bill_to', $bill_to);
            }

            $status = $request->input('status');
            if ($status) {
                $datas->withTrashed();
                $datas->whereIn('status_id', (array) $status);
            }

            $daterange = request()->input('sale_date');
            if($daterange != '' && $daterange != 'all'){
                $filterDate = explode(' - ', $daterange);
                $startDate = Carbon::parse($filterDate[0])->format('Y-m-d') . ' 00:00:00';
                $endDate = Carbon::parse($filterDate[1])->format('Y-m-d') . ' 23:59:59'; 
                $datas->whereBetween('created_at', [$startDate, $endDate]);
            }


            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new MaterialIssueCollection($datas));
        }
        return view('admin.material-issue.list'); 
    }



    public function create(Request $request){

        return view('admin.material-issue.create');
        
    }

    

    

    public function store(Request $request){
        $validated = $request->validate([
            'department' => 'required|exists:departments,id',
            'material_issue_date' => 'required|date',
            'remarks' => 'nullable|string|max:255',
            

            'kt_docs_repeater_advanced' => 'required|array|min:1',
            'kt_docs_repeater_advanced.*.product' => 'required|exists:products,id',
            'kt_docs_repeater_advanced.*.quantity' => 'required|numeric|min:0.01',
            'kt_docs_repeater_advanced.*.item_per_packet' => 'required|exists:product_attributes,id',
            'kt_docs_repeater_advanced.*.weight_per_piece' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.total_weight' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.remarks' => 'nullable',


        ], [

            'kt_docs_repeater_advanced.required' => 'Please add at least one product.',
            'kt_docs_repeater_advanced.min' => 'At least one product item is required.',

            'kt_docs_repeater_advanced.*.product.required' => 'Product is required for each row.',
            'kt_docs_repeater_advanced.*.product.exists' => 'Selected product is invalid.',
            'kt_docs_repeater_advanced.*.quantity.required' => 'Quantity is required.',
            'kt_docs_repeater_advanced.*.quantity.numeric' => 'Quantity must be a number.',
            'kt_docs_repeater_advanced.*.quantity.min' => 'Quantity must be at least 1 Sheet.',
            'kt_docs_repeater_advanced.*.item_per_packet.required' => 'Please select sheet per packet.',
            'kt_docs_repeater_advanced.*.item_per_packet.exists' => 'Selected sheet per packet is invalid.',
            'kt_docs_repeater_advanced.*.weight_per_piece.required' => 'Weight per packet is required.',
            'kt_docs_repeater_advanced.*.weight_per_piece.numeric' => 'Weight per packet must be numeric.',
            'kt_docs_repeater_advanced.*.total_weight.required' => 'Total weight is required.',
            'kt_docs_repeater_advanced.*.total_weight.numeric' => 'Total weight must be numeric.',
        ]);


        foreach ($validated['kt_docs_repeater_advanced'] as $index => $item) {
            $stockCheck = Stock::where([ 
                'product_attribute_id' => $item['item_per_packet'],
                'product_id' => $item['product'],
            ])->first();

            
            if ($item['quantity'] > $stockCheck->quantity) {
                return response()->json([
                    'class' => 'bg-danger',
                    'error' => true,
                    'message' => "Row " . ($index + 1) . ": Quantity ({$item['quantity']}) cannot exceed current stock ({$stockCheck->quantity}).",
                ], 422);
            }
        }

        DB::beginTransaction();
        try {
            $material_issue = MaterialIssue::create([
                'create_by' =>  auth('admin')->user()->id,
                'material_issue_type' => 'Manual',
                'department_id' => $validated['department'],
                'material_issue_date' => $validated['material_issue_date'] ? Carbon::parse($validated['material_issue_date']) : null,
                'status_id' => 3,
                'remarks' => $validated['remarks'] ?? null,
            ]);

            foreach ($validated['kt_docs_repeater_advanced'] as $item) {
                $stock = Stock::where([
                    'product_id' => $item['product'],
                    'product_attribute_id' => $item['item_per_packet'],
                ])->first();

                $stock->quantity = ($stock->quantity ?? 0) - $item['quantity']; // subtract for material_issues
                $stock->save();

                $material_issue->items()->create([
                    'product_id' => $item['product'],
                    'product_attribute_id' => $item['item_per_packet'],
                    'quantity' => $item['quantity'],
                    'weight' => $item['total_weight'],
                    'remarks' => $item['remarks'],
                    'status_id' => 3,
                ]);

                ProductLedger::create([
                    'product_id' => $item['product'],
                    'product_attribute_id' => $item['item_per_packet'],
                    'reference_no' => $material_issue->material_issue_number,
                    'type' => 'out',
                    'old_quantity' => $stock->quantity + $item['quantity'],
                    'new_quantity' => $item['quantity'],
                    'current_quantity' => $stock->quantity,
                    'source_type' => 'MaterialIssue',
                    'source_id' => $material_issue->id,
                    'note' => 'Material Issue Menual',
                    'created_by' => auth('admin')->id(),
                ]);
            }

            DB::commit();
            return response()->json([
                'class' => 'bg-success',
                'error' => false,
                'message' => 'Material Issue Saved Successfully',
                'call_back' => route('admin.material-issue.index'),
                'table_referesh' => true,
                'model_id' => 'dataSave'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'class' => 'bg-danger',
                'error' => true,
                'message' => 'Sale Order Not Saved. ' . $e->getMessage(),
                'debug' => $e->getTraceAsString(),
            ]);
        }
    }







    public function edit($id){
        $material_issue = MaterialIssue::with('items')->findOrFail($id);
        return view('admin.material-issue.edit', compact('material_issue'));
    }






    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'department' => 'required|exists:departments,id',
            'material_issue_date' => 'required|date',
            'remarks' => 'nullable|string|max:255',

            'kt_docs_repeater_advanced' => 'required|array|min:1',
            'kt_docs_repeater_advanced.*.item_id' => 'nullable|exists:material_issue_items,id',
            'kt_docs_repeater_advanced.*.product' => 'required|exists:products,id',
            'kt_docs_repeater_advanced.*.item_per_packet' => 'required|exists:product_attributes,id',
            'kt_docs_repeater_advanced.*.quantity' => 'required|numeric|min:0.01',
            'kt_docs_repeater_advanced.*.weight_per_piece' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.total_weight' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.remarks' => 'nullable',
        ]);

        foreach ($validated['kt_docs_repeater_advanced'] as $index => $item) {

            $stockCheck = Stock::where([
                'product_id' => $item['product'],
                'product_attribute_id' => $item['item_per_packet'],
            ])->first();

            if (!$stockCheck) {
                throw ValidationException::withMessages([
                    "kt_docs_repeater_advanced.$index.quantity" =>
                    "Stock not available for selected product.",
                ]);
            }

            $materialIssueItem = MaterialIssueItem::find($item['item_id'] ?? null);

            if ($materialIssueItem) {
                $sameProduct =
                $materialIssueItem->product_id == $item['product'] &&
                $materialIssueItem->product_attribute_id == $item['item_per_packet'];

                $allowedStock = $sameProduct
                ? $stockCheck->quantity + $materialIssueItem->quantity
                : $stockCheck->quantity;
            } else {
                $allowedStock = $stockCheck->quantity;
            }

            if ($item['quantity'] > $allowedStock) {
                throw ValidationException::withMessages([
                    "kt_docs_repeater_advanced.$index.quantity" =>
                    "Quantity ({$item['quantity']}) cannot exceed available stock ({$allowedStock}).",
                ]);
            }
        }

        DB::beginTransaction();

        try {

            $materialIssue = MaterialIssue::with('items')->findOrFail($id);

            /* ---------- HANDLE REMOVED ITEMS ---------- */
            $existingItemIds = $materialIssue->items->pluck('id')->toArray();
            $requestItemIds = collect($validated['kt_docs_repeater_advanced'])
            ->pluck('item_id')
            ->filter()
            ->toArray();

            $deletedItemIds = array_diff($existingItemIds, $requestItemIds);

            foreach ($deletedItemIds as $deletedItemId) {

                $deletedItem = MaterialIssueItem::find($deletedItemId);
                if (!$deletedItem) continue;

                $stock = Stock::lockForUpdate()->firstOrCreate(
                    [
                        'product_id' => $deletedItem->product_id,
                        'product_attribute_id' => $deletedItem->product_attribute_id,
                    ],
                    ['quantity' => 0]
                );

                $oldStock = $stock->quantity;
                $stock->quantity += $deletedItem->quantity;
                $stock->save();

                ProductLedger::create([
                    'product_id' => $deletedItem->product_id,
                    'product_attribute_id' => $deletedItem->product_attribute_id,
                    'reference_no' => $materialIssue->material_issue_number,
                    'type' => 'in',
                    'old_quantity' => $oldStock,
                    'new_quantity' => $deletedItem->quantity,
                    'current_quantity' => $stock->quantity,
                    'source_type' => 'MaterialIssue',
                    'source_id' => $materialIssue->id,
                    'note' => 'Item removed from issue',
                    'created_by' => auth('admin')->id(),
                ]);

                $deletedItem->delete();
            }

            /* ---------- UPDATE ISSUE MASTER ---------- */
            $materialIssue->update([
                'create_by' => auth('admin')->id(),
                'department_id' => $validated['department'],
                'material_issue_date' => Carbon::parse($validated['material_issue_date']),
                'remarks' => $validated['remarks'] ?? null,
                'status_id' => 3,
            ]);

            /* ---------- UPDATE / CREATE ITEMS ---------- */
            foreach ($validated['kt_docs_repeater_advanced'] as $item) {

                $issueItem = isset($item['item_id'])
                ? MaterialIssueItem::find($item['item_id'])
                : null;

                $oldQuantity = 0;

                if ($issueItem) {
                    $productChanged =
                    $issueItem->product_id != $item['product'] ||
                    $issueItem->product_attribute_id != $item['item_per_packet'];

                    if ($productChanged) {

                        $oldStock = Stock::lockForUpdate()->firstOrCreate(
                            [
                                'product_id' => $issueItem->product_id,
                                'product_attribute_id' => $issueItem->product_attribute_id,
                            ],
                            ['quantity' => 0]
                        );

                        $prevQty = $oldStock->quantity;
                        $oldStock->quantity += $issueItem->quantity;
                        $oldStock->save();

                        ProductLedger::create([
                            'product_id' => $issueItem->product_id,
                            'product_attribute_id' => $issueItem->product_attribute_id,
                            'reference_no' => $materialIssue->material_issue_number,
                            'type' => 'in',
                            'old_quantity' => $prevQty,
                            'new_quantity' => $issueItem->quantity,
                            'current_quantity' => $oldStock->quantity,
                            'source_type' => 'MaterialIssue',
                            'source_id' => $materialIssue->id,
                            'note' => 'Product changed (reversal)',
                            'created_by' => auth('admin')->id(),
                        ]);

                    } else {
                        $oldQuantity = $issueItem->quantity;
                    }
                }

                $stock = Stock::lockForUpdate()->firstOrCreate(
                    [
                        'product_id' => $item['product'],
                        'product_attribute_id' => $item['item_per_packet'],
                    ],
                    ['quantity' => 0]
                );

                $diff = $item['quantity'] - $oldQuantity;

                if ($diff != 0) {
                    $before = $stock->quantity;
                    $stock->quantity -= $diff;
                    $stock->save();

                    ProductLedger::create([
                        'product_id' => $item['product'],
                        'product_attribute_id' => $item['item_per_packet'],
                        'reference_no' => $materialIssue->material_issue_number,
                        'type' => $diff > 0 ? 'out' : 'in',
                        'old_quantity' => $before,
                        'new_quantity' => abs($diff),
                        'current_quantity' => $stock->quantity,
                        'source_type' => 'MaterialIssue',
                        'source_id' => $materialIssue->id,
                        'note' => 'Material issue updated',
                        'created_by' => auth('admin')->id(),
                    ]);
                }

                if ($issueItem) {
                    $issueItem->update([
                        'product_id' => $item['product'],
                        'product_attribute_id' => $item['item_per_packet'],
                        'quantity' => $item['quantity'],
                        'weight' => $item['total_weight'],
                        'remarks' => $item['remarks'] ?? null,
                        'status_id' => 3,
                    ]);
                } else {
                    $materialIssue->items()->create([
                        'product_id' => $item['product'],
                        'product_attribute_id' => $item['item_per_packet'],
                        'quantity' => $item['quantity'],
                        'weight' => $item['total_weight'],
                        'remarks' => $item['remarks'] ?? null,
                        'status_id' => 3,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'class' => 'bg-success',
                'error' => false,
                'message' => 'Material Issue Updated Successfully',
                'call_back' => route('admin.material-issue.index'),
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'class' => 'bg-danger',
                'error' => true,
                'message' => $e->getMessage(),
            ], 422);
        }
    }





    public function show($id){
        $material_issue = MaterialIssue::with('items')->findOrFail($id);
        return view('admin.material-issue.view', compact('material_issue'));
    }


    public function downloadPdf($saleId){
        $sale = Sale::with([
            'items.product.paperType',
            'items.productAttribute',
            'godown.city', 'godown.state',
            'billto.city', 'billto.state',
            'shipTo.city', 'shipTo.state',
            'createdBy'
        ])->findOrFail($saleId);
        $sale->download_count += 1;
        $sale->save();
        $pdf = Pdf::loadView('admin.sale.pdf', compact('sale'))->setPaper('a4', 'portrait');

        $filename = 'SaleOrder-' . str_replace(['/', '\\'], '-', $sale->order_no) . '.pdf';
        return $pdf->download($filename);
    }

    public function addInvoice($id){
        $sale = Sale::where('id', $id)->with(['billTo'])->first();
        return view('admin.sale.add-invoice', compact('sale'));
    }



    public function updateInvoice(Request $request, $id){
        $validated = $request->validate([
            'invoice_number' => 'required|string|max:191',
        //'invoice_date' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            $sale = Sale::with('items')->findOrFail($id);
            $previousStatus = $sale->status_id;


            $sale->invoice_no = $validated['invoice_number'];
            $sale->invoice_date = $sale->invoice_date??Carbon::today();


            if($sale->status_id == 1){
                foreach ($sale->items as $item) {
                    $attribute = ProductAttribute::findOrFail($item->product_attribute_id);
                    $today = Carbon::today()->toDateString();

                    $dailyReport = DailyReport::firstOrCreate(
                        [
                    'godown_id' => $sale->godown_id, // Assuming godown_id comes from $sale
                    'report_date' => $today,
                ],
                [
                    'opening_stock_weight' => 0,
                    'opening_stock' => 0,
                    'inward_quantity' => 0,
                    'inward_weight' => 0,
                    'reconciliation_inward_quantity' => 0,
                    'reconciliation_inward_weight' => 0,
                    'new_booked_quantity' => 0,
                    'new_booked_weight' => 0,
                    'opening_booked_quantity' => 0,
                    'opening_booked_weight' => 0,
                    'cancelled_booked_quantity' => 0,
                    'cancelled_booked_weight' => 0,
                    'closing_booked_quantity' => 0,
                    'closing_booked_weight' => 0,
                    'sale_quantity' => 0,
                    'sale_weight' => 0,
                    'cancelled_sale_quantity' => 0,
                    'cancelled_sale_weight' => 0,
                    'reconciliation_in' => 0,
                    'reconciliation_in_weight' => 0,
                    'reconciliation_out' => 0,
                    'reconciliation_out_weight' => 0,
                    'closing_stock' => 0,
                    'closing_stock_weight' => 0,
                ]
            );

                    $weight = abs($item->quantity) * $attribute->weight_per_piece;
                    $roundedWeight = customRoundSale($weight);

                    $dailyReport->sale_quantity += abs($item->quantity);
                    $dailyReport->sale_weight += $roundedWeight;
                    $dailyReport->closing_booked_quantity -= abs($item->quantity);
                    $dailyReport->closing_booked_weight -= $roundedWeight;

                    $dailyReport->save();
                }
            }

            $sale->status_id = 3;
            $sale->save();

            if ($request->send_mail == 1) {
                $pdf = PDF::loadView('admin.sale.pdf', compact('sale'))->setPaper('a4', 'portrait');
                $pdfContent = $pdf->output();
                $filename = 'SaleOrder-' . str_replace(['/', '\\'], '-', $sale->order_no) . '.pdf';

                $to = $sale->billto->email ?? 'asifjamal251@yahoo.in';
                $ccRaw = $sale->billto->cc_emails ?? '';
                $ccEmails = array_filter(array_map('trim', explode(',', $ccRaw)));

                Mail::to($to)
                ->cc($ccEmails)
                ->send(new SaleInvoiceMail($sale, $filename, $pdfContent));
            }



            DB::commit();

            return response()->json([
                'class' => 'bg-success',
                'error' => false,
                'message' => 'Invoice Updated Successfully',
                'call_back' => '',
                'table_referesh' => true,
                'model_id' => 'dataSave'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'class' => 'bg-danger',
                'error' => true,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }




    public function destroy($id){
        DB::beginTransaction();

        try {
            $sale = Sale::with(['items.productAttribute'])->findOrFail($id);
            $client = Client::find($sale->bill_to);
            $note = 'Sale cancelled for client: ' . ($client ? $client->company_name : 'Unknown Client');
            $today = Carbon::today()->toDateString();

            foreach ($sale->items as $item) {
                if ($item->deleted_at) {
                    continue; 
                }

                $item->deleted_at = now();
                $item->save();

                // Stock adjustment
                $stock = Stock::where([
                    'product_id' => $item->product_id,
                    'product_attribute_id' => $item->product_attribute_id,
                    'godown_id' => $sale->godown_id,
                ])->first();

                $oldStockQty = $stock->quantity ?? 0;
                $newStockQty = $oldStockQty + $item->quantity;

                // Ledger
                ProductLedger::create([
                    'product_id' => $item->product_id,
                    'product_attribute_id' => $item->product_attribute_id,
                    'godown_id' => $sale->godown_id,
                    'reference_no' => $sale->order_no,
                    'type' => 'in',
                    'old_quantity' => $oldStockQty,
                    'new_quantity' => $item->quantity,
                    'current_quantity' => $newStockQty,
                    'source_type' => 'Sale',
                    'source_id' => $sale->id,
                    'note' => $note,
                    'created_by' => auth('admin')->user()->id,
                ]);

                if ($stock) {
                    $stock->quantity = $newStockQty;
                    $stock->save();
                }

                // Daily Report
                $attribute = $item->productAttribute;

                $dailyReport = DailyReport::firstOrCreate(
                    [
                        'godown_id' => $sale->godown_id,
                        'report_date' => $today,
                    ],
                    [
                        'opening_stock_weight' => 0,
                        'opening_stock' => 0,
                        'inward_quantity' => 0,
                        'inward_weight' => 0,
                        'reconciliation_inward_quantity' => 0,
                        'reconciliation_inward_weight' => 0,
                        'new_booked_quantity' => 0,
                        'new_booked_weight' => 0,
                        'opening_booked_quantity' => 0,
                        'opening_booked_weight' => 0,
                        'cancelled_booked_quantity' => 0,
                        'cancelled_booked_weight' => 0,
                        'closing_booked_quantity' => 0,
                        'closing_booked_weight' => 0,
                        'sale_quantity' => 0,
                        'sale_weight' => 0,
                        'cancelled_sale_quantity' => 0,
                        'cancelled_sale_weight' => 0,
                        'reconciliation_in' => 0,
                        'reconciliation_in_weight' => 0,
                        'reconciliation_out' => 0,
                        'reconciliation_out_weight' => 0,
                        'closing_stock' => 0,
                        'closing_stock_weight' => 0,   
                    ]
                );

                $weight = $item->quantity * $attribute->weight_per_piece;
                $roundedWeight = customRoundSale($weight);

                if($sale->status_id == 1){
                    $dailyReport->cancelled_booked_quantity += $item->quantity;
                    $dailyReport->cancelled_booked_weight += $roundedWeight;

                    $dailyReport->closing_booked_quantity -= $item->quantity;
                    $dailyReport->closing_booked_weight -= $roundedWeight;
                } else{
                    $dailyReport->cancelled_sale_quantity += $item->quantity;
                    $dailyReport->cancelled_sale_weight += $roundedWeight;
                }

                $dailyReport->closing_stock += $item->quantity;
                $dailyReport->closing_stock_weight += $roundedWeight;
                $dailyReport->save();
            }

            // Mark sale as cancelled
            $sale->status_id = 5;
            $sale->deleted_at = now();
            $sale->save();

            DB::commit();

            return response()->json([
                'class' => 'bg-success',
                'message' => 'Sale and items cancelled successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'class' => 'bg-error',
                'message' => 'Error cancelling sale: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroyItem($id){
        $order = Sale::with('items')->findOrFail($id);
        return view('admin.sale.cancel', compact('order'));
    }


    public function itemDestroy(Request $request, $id){
        DB::beginTransaction();

        try {
            $material_issue_item = SaleItem::with('productAttribute', 'sale')->findOrFail($id);

            // Soft-delete item and update remarks
            $material_issue_item->remarks = $request->remarks ?? $material_issue_item->remarks;
            $material_issue_item->deleted_at = now();
            $material_issue_item->save();

            $productId = $material_issue_item->product_id;
            $attributeId = $material_issue_item->product_attribute_id;
            $godownId = $material_issue_item->sale->godown_id;
            $quantity = $material_issue_item->quantity;

            // Restore stock
            $stock = Stock::where([
                'product_id' => $productId,
                'product_attribute_id' => $attributeId,
                'godown_id' => $godownId,
            ])->first();

            $oldStockQty = $stock->quantity ?? 0;
            $newStockQty = $oldStockQty + $quantity;

            // Ledger entry
            $client = Client::find($material_issue_item->sale->bill_to);
            $note = 'Sale cancelled for client: ' . ($client ? $client->company_name : 'Unknown Client');

            ProductLedger::create([
                'product_id' => $productId,
                'product_attribute_id' => $attributeId,
                'godown_id' => $godownId,
                'reference_no' => $material_issue_item->sale->order_no,
                'type' => 'in',
                'old_quantity' => $oldStockQty,
                'new_quantity' => $quantity,
                'current_quantity' => $newStockQty,
                'source_type' => 'Sale',
                'source_id' => $material_issue_item->sale_id,
                'note' => $note,
                'created_by' => auth('admin')->user()->id,
            ]);

            // Update stock
            if ($stock) {
                $stock->quantity = $newStockQty;
                $stock->save();
            }

            // Update daily report
            $attribute = $material_issue_item->productAttribute;
            $today = Carbon::today()->toDateString();

            $dailyReport = DailyReport::firstOrCreate(
                [
                    'godown_id' => $godownId,
                    'report_date' => $today,
                ],
                [
                    'opening_stock_weight' => 0,
                    'opening_stock' => 0,
                    'inward_quantity' => 0,
                    'inward_weight' => 0,
                    'reconciliation_inward_quantity' => 0,
                    'reconciliation_inward_weight' => 0,
                    'new_booked_quantity' => 0,
                    'new_booked_weight' => 0,
                    'opening_booked_quantity' => 0,
                    'opening_booked_weight' => 0,
                    'cancelled_booked_quantity' => 0,
                    'cancelled_booked_weight' => 0,
                    'closing_booked_quantity' => 0,
                    'closing_booked_weight' => 0,
                    'sale_quantity' => 0,
                    'sale_weight' => 0,
                    'cancelled_sale_quantity' => 0,
                    'cancelled_sale_weight' => 0,
                    'reconciliation_in' => 0,
                    'reconciliation_in_weight' => 0,
                    'reconciliation_out' => 0,
                    'reconciliation_out_weight' => 0,
                    'closing_stock' => 0,
                    'closing_stock_weight' => 0,   
                ]
            );

            $weight = $quantity * $attribute->weight_per_piece;
            $roundedWeight = customRoundSale($weight);

            if($material_issue_item->sale->status_id == 1){
                $dailyReport->cancelled_booked_quantity += $quantity;
                $dailyReport->cancelled_booked_weight += $roundedWeight;

                $dailyReport->closing_booked_quantity -= $quantity;
                $dailyReport->closing_booked_weight -= $roundedWeight;
            } else{
                $dailyReport->cancelled_sale_quantity += $quantity;
                $dailyReport->cancelled_sale_weight += $roundedWeight;
            }

            $dailyReport->closing_stock += $quantity;
            $dailyReport->closing_stock_weight += $roundedWeight;
            $dailyReport->save();

            // If all sale items are deleted, update sale status
            $remainingItems = SaleItem::where('sale_id', $material_issue_item->sale_id)
            ->whereNull('deleted_at')
            ->count();

            if ($remainingItems === 0) {
                $sale = Sale::find($material_issue_item->sale_id);
                $sale->status_id = 5; // Cancelled
                $material_issue_item->deleted_at = now();
                $sale->save();
            }

            DB::commit();

            return response()->json([
                'class' => 'bg-success',
                'message' => 'Item deleted and stock updated.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting item: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function showCancelled(Request $request){
        if ($request->ajax()) {
            $query = SaleItem::onlyTrashed()
            ->with([
                'product',
                'sale' => fn ($q) => $q->withTrashed()->with(['godown', 'billTo', 'shipTo']),
            ])
            ->orderBy('deleted_at', 'desc');

            if ($search = $request->input('search')) {
                $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name_cm', 'like', "%$search%")
                    ->orWhere('name_inch', 'like', "%$search%")
                    ->orWhere('gsm', 'like', "%$search%");
                })
                ->orWhereHas('sale', function ($q) use ($search) {
                    $q->withTrashed()
                    ->where('order_no', 'like', "%$search%")
                    ->orWhere('invoice_no', 'like', "%$search%")
                    ->orWhere('dispatch_through', 'like', "%$search%")
                    ->orWhere('vehicle_no', 'like', "%$search%");
                });
            }
            if ($godownIds = $request->input('godown')) {
                $query->whereHas('sale', fn ($q) => $q->withTrashed()->whereIn('godown_id', $godownIds));
            }

            if ($shipTo = $request->input('ship_to')) {
                $query->whereHas('sale', fn ($q) => $q->withTrashed()->where('ship_to', $shipTo));
            }

            if ($billTo = $request->input('bill_to')) {
                $query->whereHas('sale', fn ($q) => $q->withTrashed()->where('bill_to', $billTo));
            }

            $totaldata = $query->count();

            $records = $query
            ->skip($request->start)
            ->take($request->length)
            ->get();

            $result = [
                "length" => $request->length,
                "recordsTotal" => $totaldata,
                "recordsFiltered" => $totaldata,
                "data" => [],
            ];

            foreach ($records as $index => $data) {
                $sale = $data->sale;
                $ledgerEntry = optional($sale?->ledger()
                    ->where('note', 'like', '%cancel%')
                    ->latest()
                    ->first());

                $result['data'][] = [
                    'sn' => $request->start + $index + 1,
                    'id' => $data->id,
                    'product' => optional($data->product)->all_name,
                    'order_no' => optional($sale)->order_no,
                    'godown' => optional($sale?->godown)->display_name,
                    'bill_to' => optional($sale?->billTo)->company_name,
                    'ship_to' => optional($sale?->shipTo)->company_name,
                    'so_date' => '<p class="m-0">' . optional($sale?->so_date)?->format('d F, Y') . '</p>'
                    . ($data->deleted_at ? '<p class="m-0 text-danger">' . $data->deleted_at->format('d F, Y') . '</p>' : ''),
                    'cancelled_by' => $ledgerEntry?->createdBy?->name ?? 'System',
                ];
            }

            return $result;
        }

        return view('admin.sale.cancel-list');
    }


    public function export(){
        return view('admin.sale.export');
    }


}

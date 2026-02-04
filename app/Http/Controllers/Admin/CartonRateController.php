<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CartonRateExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CartonRate\CartonRateCollection;
use App\Models\Dye;
use App\Models\Item;
use App\Models\ItemProcessDetail;
use App\Models\JobCard;
use App\Models\JobCardItem;
use App\Models\OrderSheet;
use App\Models\ProcessingItem;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CartonRateController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $datas = JobCardItem::orderByRaw("CASE
                WHEN status_id = 1 THEN 1
                WHEN status_id = 6 THEN 2
                WHEN status_id = 3 THEN 3
                ELSE 4
                END")->whereNotIn('status_id', [5])
            ->orderBy('created_at', 'desc');
            $totaldata = $datas->count();

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
                $datas->where('item_name', 'LIKE', '%'.$request->item_name.'%');
            }

            if ($request->filled('item_size')) {
                $datas->where('item_size', 'LIKE', '%'.$request->item_size.'%');
            }

            if ($request->filled('set_no')) {
                $datas->whereHas('jobCard', function ($q) use ($request) {
                    $q->where('set_number', 'LIKE', '%'.$request->set_no.'%');
                });
            }

            $status = $request->input('status');
            if ($status) {
                $datas->where('status_id', $status);
            }
            
            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new CartonRateCollection($datas));
        }
        return view('admin.carton-rate.list');
    }

    public function update(Request $request){
        $request->validate([
            'id'   => 'required|exists:job_card_items,id',
            'rate' => 'nullable|numeric|min:0',
        ]);



        $jobCardItem = JobCardItem::where('id', $request->id)
            ->whereIn('status_id', [1, 6])
            ->firstOrFail();

        ItemProcessDetail::where(
            'id',
            $jobCardItem->item_process_details_id
        )->update([
            'rate' => $request->rate??0
        ]);

        PurchaseOrderItem::where(
            'id',
            $jobCardItem->purchase_order_item_id
        )->update([
            'rate' => $request->rate??0
        ]);

        $jobCardItem->update([
            'rate'      => $request->rate,
            //'status_id' => $request->rate > 0 ? 6 : 1
        ]);

        return response()->json([
            'message' => 'Rate updated successfully.',
            'title'   => 'Carton Rate Updated.',
            'class'   => 'bg-success'
        ]);
    }



    public function rateCompleted(Request $request){
        $request->validate([
            'id'   => 'required|exists:job_card_items,id',
        ]);

        $jobCardItem = JobCardItem::where('id', $request->id)
            ->whereIn('status_id', [1])
            ->firstOrFail();

        $jobCardItem->update([
            //'rate'      => $request->rate,
            'status_id' => 6,
        ]);

        return response()->json([
            'message' => 'Rate updated successfully.',
            'title'   => 'Carton Rate Updated.',
            'class'   => 'bg-success'
        ]);
    }


    public function exportForm(){
        return view('admin.carton-rate.export');
    }


    public function export(Request $request){
        $filters = $request->only([
            'export_status',
            'export_po_date',
            'mfg_by',
            'mkdt_by',
        ]);

        $filename = 'carton_rate.xlsx';

        Excel::store(
            new CartonRateExport($filters),
            'excel/' . $filename,
            'public'
        );

        return response()->json([
            'message'  => 'Carton rate exported successfully.',
            'filename' => asset('storage/excel/' . $filename),
            'class'    => 'success'
        ]);
    }



    public function updateApproved(Request $request){
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'required|integer|exists:job_card_items,id',
        ]);

        // check rate must not be null/empty/0
        $invalidItems = JobCardItem::whereIn('id', $request->ids)
            ->where(function ($q) {
                $q->whereNull('rate')
                  ->orWhere('rate', '=', 0)
                  ->orWhere('rate', '=', '');
            })
            ->pluck('id');

        if ($invalidItems->count() > 0) {
            return response()->json([
                'class' => 'bg-danger',
                'error' => true,
                'message' => 'Some Item Rate is missing',
                'table_refresh' => false,
            ], 422);
        }

        JobCardItem::whereIn('id', $request->ids)->update([
            'status_id' => 6
        ]);

        return response()->json([
            'class' => 'bg-success',
            'error' => false,
            'message' => 'Approved Saved Successfully',
            'call_back' => '',
            'table_refresh' => true,
            'model_id' => 'dataSave'
        ]);
    }

}

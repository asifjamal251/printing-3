<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Printing\PrintingCollection;
use App\Models\JobCardStage;
use App\Models\Operator;
use App\Models\Printing;
use App\Services\JobCardStageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrintingController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $admin = auth('admin')->user();

            $datas = Printing::orderByRaw("
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

            if ($request->filled('status')) {
                $datas->where('status_id', $request->status);
            }

            if ($request->filled('operator')) {
                $datas->where('operator_id', $request->operator);
            }

            if ($request->filled('set_no')) {
                $datas->whereHas('jobCard', function ($q) use ($request) {
                    $q->where('set_number', 'LIKE', '%' . $request->set_no . '%');
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
            }
            
            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new PrintingCollection($datas));
        }
        return view('admin.printing.list');
    }

    public function updateOperator(Request $request){
        if($request->operator_id){
            $printing = Printing::where('id', $request->id)->first();
            $operator = Operator::where('id', $request->operator_id)->first();
            $printing->update(['operator_id' => $operator->id, 'admin_id' => $operator->admin_id]);
            JobCardStage::where('id', $printing->job_card_stage_id)->update(['operator_id' => $operator->id]);
            return response()->json([
                'message' => 'Operator Updated successfully.',
                'title' => 'Printing Updated.',
                'class' => 'bg-success'
            ]);
        }
        return response()->json([
            'message' => 'Something went wrong.',
            'title' => 'Printing.',
            'class' => 'bg-ganger'
        ]);
    }

    public function updateCounter(Request $request){
        if (!$request->counter) {
            return response()->json([
                'message' => 'Counter is required.',
                'title'   => 'Printing',
                'class'   => 'bg-danger'
            ]);
        }

        try {

            DB::transaction(function () use ($request) {

                $printing = Printing::findOrFail($request->id);

                if (
                    empty($printing->operator_id) ||
                    empty($printing->admin_id)
                ) {
                    throw new \Exception('Operator is not assigned.');
                }

                JobCardStageService::updateStageCounter(
                    $printing->stage,
                    (int) $request->counter
                );

                $printing->update([
                    'counter'       => $request->counter,
                    'completed_at'  => now(),
                    'completed_by'  => auth('admin')->user()->id,
                    'status_id'     => 3,
                ]);
            });

        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage(),
                'title'   => 'Printing',
                'class'   => 'bg-danger'
            ]);
        }

        return response()->json([
            'message' => 'Counter Updated successfully.',
            'title'   => 'Printing Updated.',
            'class'   => 'bg-success'
        ]);
    }


    public function cancel(Request $request){
        try {
            $printing = Printing::findOrFail($request->id);
            JobCardStageService::cancelStage($printing->stage);

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


    

}

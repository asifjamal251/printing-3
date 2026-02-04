<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Lamination\LaminationCollection;
use App\Models\JobCardStage;
use App\Models\Operator;
use App\Models\Lamination;
use App\Services\JobCardStageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaminationController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $admin = auth('admin')->user();

            $datas = Lamination::orderByRaw("
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
            
            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new LaminationCollection($datas));
        }
        return view('admin.lamination.list');
    }

    public function updateOperator(Request $request){
        if($request->operator_id){
            $lamination = Lamination::where('id', $request->id)->first();
            $operator = Operator::where('id', $request->operator_id)->first();
            $lamination->update(['operator_id' => $operator->id, 'admin_id' => $operator->admin_id]);
            JobCardStage::where('id', $lamination->job_card_stage_id)->update(['operator_id' => $operator->id]);
            return response()->json([
                'message' => 'Operator Updated successfully.',
                'title' => 'Lamination Updated.',
                'class' => 'bg-success'
            ]);
        }
        return response()->json([
            'message' => 'Something went wrong.',
            'title' => 'Lamination.',
            'class' => 'bg-ganger'
        ]);
    }

    public function updateCounter(Request $request){
        if (!$request->counter) {
            return response()->json([
                'message' => 'Counter is required.',
                'title'   => 'Lamination',
                'class'   => 'bg-danger'
            ]);
        }

        try {

            DB::transaction(function () use ($request) {

                $lamination = Lamination::findOrFail($request->id);

                if (
                    empty($lamination->operator_id) ||
                    empty($lamination->admin_id)
                ) {
                    throw new \Exception('Operator is not assigned.');
                }

                JobCardStageService::updateStageCounter(
                    $lamination->stage,
                    (int) $request->counter
                );

                $lamination->update([
                    'counter'       => $request->counter,
                    'completed_at'  => now(),
                    'completed_by'  => auth('admin')->user()->id,
                    'status_id'     => 3,
                ]);
            });

        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage(),
                'title'   => 'Lamination',
                'class'   => 'bg-danger'
            ]);
        }

        return response()->json([
            'message' => 'Counter Updated successfully.',
            'title'   => 'Lamination Updated.',
            'class'   => 'bg-success'
        ]);
    }


    public function cancel(Request $request){
        try {
            $lamination = Lamination::findOrFail($request->id);
            JobCardStageService::cancelStage($lamination->stage);

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

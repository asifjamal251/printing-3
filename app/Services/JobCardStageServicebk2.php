<?php
namespace App\Services;

use App\Models\JobCard;
use App\Models\JobCardStage;
use App\Models\PurchaseOrderItem;
use App\Models\Status;
use Illuminate\Support\Facades\DB;

class JobCardStageService
{
    public static function updateStageCounter(JobCardStage $stage, int $outCounter){
        if ($outCounter > $stage->in_counter) {
            throw new \Exception("Out counter cannot exceed In counter for stage: {$stage->name}");
        }


        $stage->out_counter = $outCounter;
        $stage->status_id   = 3; 
        $stage->save();

        $nextStage = JobCardStage::where('job_card_id', $stage->job_card_id)
            ->where('order', '>', $stage->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($nextStage) {
            $nextStage->in_counter = $outCounter;
            $nextStage->save();
            $modelClass = "App\\Models\\" . $nextStage->name;

            if (class_exists($modelClass)) {
                $modelInstance = $modelClass::updateOrCreate(
                    [
                        'job_card_id' => $nextStage->job_card_id,
                        'stage_id'    => $nextStage->id,
                    ],
                    [
                        'status_id'   => 1, 
                    ]
                );

                $statusName = "On " . preg_replace('/(?<!^)([A-Z])/', ' $1', $nextStage->name);
                $status     = Status::where('name', $statusName)->first();

                if ($status) {
                    $jobCard = $stage->jobCard()->with('items')->first();

                    foreach ($jobCard->items as $item) {
                        if ($item->purchase_order_item_id) {
                            \App\Models\PurchaseOrderItem::where('id', $item->purchase_order_item_id)
                                ->update(['status_id' => $status->id]);
                        }
                    }
                } else {
                    throw new \Exception("Status '{$statusName}' not found in Status table.");
                }

            } else {
                throw new \Exception("Model {$modelClass} does not exist.");
            }
        }
    }


    public static function cancelStage(JobCardStage $stage){
        DB::transaction(function () use ($stage) {
            $nextStage = JobCardStage::where('job_card_id', $stage->job_card_id)
                ->where('order', '>', $stage->order)
                ->orderBy('order', 'asc')
                ->first();

            if ($nextStage) {
                // 1. check next stage model
                $modelClass = "App\\Models\\" . $nextStage->name;
                if (class_exists($modelClass)) {
                    $modelRecord = $modelClass::where('job_card_id', $nextStage->job_card_id)
                        ->where('stage_id', $nextStage->id)
                        ->first();

                    if ($modelRecord) {
                        if ($modelRecord->status_id != 1) {
                            throw new \Exception("Model completed job on {$nextStage->name}, cancel not allowed.");
                        }

                        $modelRecord->delete();
                    }
                }

                $nextStage->update([
                    'in_counter'  => null,
                    'out_counter' => null,
                    'status_id'   => null,
                ]);
            }

            $stage->update([
                'out_counter' => null,
                'status_id'   => 1,
            ]);

            $modelClass = "App\\Models\\" . $stage->name;
            if (class_exists($modelClass)) {
                $modelClass::where('job_card_id', $stage->job_card_id)
                    ->where('stage_id', $stage->id)
                    ->update([
                        'status_id'      => 1, // pending
                        'complated_date' => null,
                    ]);
            }

            $statusName = "On " . preg_replace('/(?<!^)([A-Z])/', ' $1', $stage->name);
            $status = \App\Models\Status::where('name', $statusName)->first();

            if ($status) {
                $jobCard = $stage->jobCard()->with('items')->first();

                // update JobCard status
                $jobCard->update(['status_id' => $status->id]);

                foreach ($jobCard->items as $item) {
                    if ($item->purchase_order_item_id) {
                        \App\Models\PurchaseOrderItem::where('id', $item->purchase_order_item_id)
                            ->update(['status_id' => $status->id]);
                    }
                }
            } else {
                throw new \Exception("Status '{$statusName}' not found.");
            }
        });
    }


}
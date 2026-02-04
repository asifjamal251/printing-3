<?php

namespace App\Services;

use App\Models\JobCardStage;
use App\Models\Operator;
use App\Models\PurchaseOrderItem;
use App\Models\Status;
use Illuminate\Support\Facades\DB;

class JobCardStageService
{
    public static function updateStageCounter(JobCardStage $stage, int $outCounter)
    {
        if ($outCounter > $stage->in_counter) {
            throw new \Exception("Out counter cannot exceed In counter for stage: {$stage->name}");
        }

        DB::transaction(function () use ($stage, $outCounter) {

            // ✅ Update current stage
            $stage->out_counter += $outCounter;
            $stage->status_id   = 3; // completed
            $stage->save();



            // ✅ Find next stage
            $nextStage = JobCardStage::where('job_card_id', $stage->job_card_id)
                ->where('order', '>', $stage->order)
                ->orderBy('order', 'asc')
                ->first();

            $operator = $nextStage->operator_id
            ? Operator::find($nextStage->operator_id)
            : null;

            // ✅ Get max order
            $maxOrder = JobCardStage::where('job_card_id', $stage->job_card_id)->max('order');

            if ($nextStage) {
                $isNextStageLast = $nextStage->order == $maxOrder;

                if (! $isNextStageLast) {
                    // ========== CASE 1: next stage exists and is NOT last ==========
                    $nextStage->in_counter += $outCounter;
                    $nextStage->save();

                    $modelClass = "App\\Models\\" . $nextStage->name;
                    if (class_exists($modelClass)) {
                        $modelClass::updateOrCreate(
                            [
                                'job_card_id' => $nextStage->job_card_id,
                                'job_card_stage_id'    => $nextStage->id,
                            ],
                            [
                                'status_id'   => 1, // pending
                                'operator_id' => $operator?->id,
                                'admin_id'    => $operator?->admin_id,
                            ]
                        );
                    }

                    // update PO items status
                    $statusName = "On " . preg_replace('/(?<!^)([A-Z])/', ' $1', $nextStage->name);
                    
                    $status = Status::where('name', $statusName)->first();
                    if ($status) {
                        $jobCard = $stage->jobCard()->with('items')->first();
                        $jobCard->status_id = $status->id;
                        $jobCard->save();
                        foreach ($jobCard->items as $item) {
                            if ($item->purchase_order_item_id) {
                                PurchaseOrderItem::where('id', $item->purchase_order_item_id)
                                    ->update(['status_id' => $status->id]);
                            }
                        }
                    }
                } else {
                    // ========== CASE 2: next stage exists AND it is LAST ==========
                    $jobCard = $stage->jobCard()->with('items')->first();

                    // set counter once
                    $nextStage->in_counter += $outCounter;
                    $nextStage->save();

                    $modelClass = "App\\Models\\" . $nextStage->name;
                    if (class_exists($modelClass)) {
                        foreach ($jobCard->items as $item) {
                            $modelClass::updateOrCreate(
                                [
                                    'job_card_id'      => $nextStage->job_card_id,
                                    'job_card_item_id' => $item->id,
                                    'purchase_order_item_id' => $item->purchase_order_item_id,
                                ],
                                [
                                    'status_id'              => 1, // pending
                                    'purchase_order_id'      => $item->purchase_order_id,
                                    'item_id'      => $item->item?->id,
                                    'job_card_stage_id' => $nextStage->id,
                                    'mkdt_by'      => $item->item?->mkdt_by,
                                    'mfg_by'      => $item->item?->mfg_by,
                                ]
                            );
                        }
                    }

                    // update PO items status once
                    $statusName = "On " . preg_replace('/(?<!^)([A-Z])/', ' $1', $nextStage->name);
                    $status     = Status::where('name', $statusName)->first();
                    if ($status) {
                        foreach ($jobCard->items as $item) {
                            if ($item->purchase_order_item_id) {
                                PurchaseOrderItem::where('id', $item->purchase_order_item_id)
                                    ->update(['status_id' => $status->id]);
                            }
                        }

                        $jobCard->status_id = $status->id;
                        $jobCard->save();
                    }
                }
            } else {
                // ========== CASE 3: already at the LAST stage ==========
                $jobCard = $stage->jobCard()->with('items')->first();

                foreach ($jobCard->items as $item) {
                    \App\Models\JobCardItem::updateOrCreate(
                        [
                            'job_card_id'      => $jobCard->id,
                            'id' => $item->id,
                        ],
                        [
                            'purchase_order_id'      => $item->purchase_order_id,
                            'purchase_order_item_id' => $item->purchase_order_item_id,
                        ]
                    );
                }

                $completedStatus = Status::where('name', 'Completed')->first();
                if ($completedStatus) {
                    $jobCard->update(['status_id' => $completedStatus->id]);
                }
            }
        });
    }

   

public static function cancelStage(JobCardStage $stage)
{
    DB::transaction(function () use ($stage) {

        $nextStage = JobCardStage::where('job_card_id', $stage->job_card_id)
            ->where('order', '>', $stage->order)
            ->orderBy('order', 'asc')
            ->first();
           // dd($nextStage);

        if ($nextStage && $nextStage->status_id != 1) {
            throw new \Exception('Next stage already completed. Cancellation not allowed.');
        }

        $maxOrder = JobCardStage::where('job_card_id', $stage->job_card_id)->max('order');

        if ($nextStage) {

            $isNextStageLast = $nextStage->order == $maxOrder;

            if ($isNextStageLast) {

                $jobCard = $stage->jobCard()->with('items')->first();
                $modelClass = "App\\Models\\" . $nextStage->name;

                if (class_exists($modelClass)) {
                    foreach ($jobCard->items as $item) {
                        $modelClass::where('job_card_id', $jobCard->id)
                            ->where('job_card_item_id', $item->id)
                            ->delete();
                    }
                }

            } else {

                $modelClass = "App\\Models\\" . $nextStage->name;

                if (class_exists($modelClass)) {
                    $modelClass::where('job_card_id', $nextStage->job_card_id)
                        ->where('job_card_stage_id', $nextStage->id)
                        ->delete();
                }
            }

            $nextStage->update([
                'in_counter'  => 0,
                'out_counter' => 0,
                'status_id'   => 1,
            ]);
        }

        $stage->update([
            'out_counter' => 0,
            'status_id'   => 1,
        ]);

        $modelClass = "App\\Models\\" . $stage->name;
        if (class_exists($modelClass)) {
            $modelClass::where('job_card_id', $stage->job_card_id)
                ->where('job_card_stage_id', $stage->id)
                ->update([
                    'status_id'   => 1,
                    'completed_at' => null,
                ]);
        }

        $statusName = "On " . preg_replace('/(?<!^)([A-Z])/', ' $1', $stage->name);
        $status = Status::where('name', $statusName)->first();

        if (!$status) {
            throw new \Exception("Status '{$statusName}' not found.");
        }

        $jobCard = $stage->jobCard()->with('items')->first();
        $jobCard->update(['status_id' => $status->id]);

        foreach ($jobCard->items as $item) {
            if ($item->purchase_order_item_id) {
                PurchaseOrderItem::where('id', $item->purchase_order_item_id)
                    ->update(['status_id' => $status->id]);
            }
        }
    });
}
}
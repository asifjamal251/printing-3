<?php

namespace App\Traits;

use App\Models\JobCard;
use App\Models\JobCardStage;
use App\Models\Operator;

trait JobCardStageCommon
{
    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function stage()
    {
        return $this->belongsTo(JobCardStage::class, 'stage_id');
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id');
    }

    /* ---------------- SCOPES ---------------- */

    // Pending jobs
    public function scopePending($query)
    {
        return $query->where('status_id', 1);
    }

    // Running jobs
    public function scopeRunning($query)
    {
        return $query->where('status_id', 2);
    }

    // Completed jobs
    public function scopeCompleted($query)
    {
        return $query->where('status_id', 3);
    }

    // Jobs for a specific operator
    public function scopeForOperator($query, $operatorId)
    {
        return $query->where('operator_id', $operatorId);
    }
}
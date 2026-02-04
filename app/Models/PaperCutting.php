<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaperCutting extends Model
{
    protected $fillable = [
        'job_card_id',
        'admin_id',
        'operator_id',
        'job_card_stage_id',
        'counter',
        'completed_at',
        'completed_by',
        'status_id',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function stage()
    {
        return $this->belongsTo(JobCardStage::class, 'job_card_stage_id');
    }
}
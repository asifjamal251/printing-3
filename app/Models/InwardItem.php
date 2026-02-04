<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InwardItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inward_id',
        'quality_id',
        'job_card_id',
        'job_card_item_id',
        'gsm',
        'width',
        'allocation',
        'weight',
        'core_dia',
        'reel_dia',
        'batch',
        'handling_unit',
        'parent_inward_item_id',
        'status_id',
        'booked_at',
        'stock_date',
    ];

    protected $casts = [
        'stock_date' => 'date',
    ];

    public function inward()
    {
        return $this->belongsTo(Inward::class, 'inward_id');
    }

    public function quality()
    {
        return $this->belongsTo(Quality::class, 'quality_id');
    }

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class, 'job_card_id');
    }

    public function jobCardItem()
    {
        return $this->belongsTo(JobCardItem::class, 'job_card_item_id');
    }
}
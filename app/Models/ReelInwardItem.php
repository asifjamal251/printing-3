<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReelInwardItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inward_id',
        'parent_inward_item_id',
        'product_type_id',
        'job_card_id',
        'job_card_item_id',
        'gsm',
        'width',
        'allocation',
        'weight',
        'core_dia',
        'reel_dia',
        'lot_number',
        'reel_number',
        'booked_at',
        'status_id',
    ];

    protected $casts = [
        'booked_at' => 'datetime',
    ];

    public function inward()
    {
        return $this->belongsTo(ReelInward::class, 'inward_id');
    }

    public function parentItem()
    {
        return $this->belongsTo(ReelInwardItem::class, 'parent_inward_item_id');
    }

    public function childItems()
    {
        return $this->hasMany(ReelInwardItem::class, 'parent_inward_item_id');
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function jobCardItem()
    {
        return $this->belongsTo(JobCardItem::class);
    }
}
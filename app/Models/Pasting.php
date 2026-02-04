<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasting extends Model
{
    protected $fillable = [
        'mkdt_by',
        'mfg_by',
        'item_id',
        'job_card_id',
        'job_card_item_id',
        'purchase_order_id',
        'purchase_order_item_id',
        'admin_id',
        'operator_id',
        'job_card_stage_id',
        'completed_at',
        'completed_by',
        'status_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function items()
    {
        return $this->hasMany(PastingItem::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function mkdtBy()
    {
        return $this->belongsTo(Client::class, 'mkdt_by');
    }

    public function mfgBy()
    {
        return $this->belongsTo(Client::class, 'mfg_by');
    }

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function jobCardItem()
    {
        return $this->belongsTo(JobCardItem::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class);
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

    public function completedBy()
    {
        return $this->belongsTo(Admin::class, 'completed_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes (Useful)
    |--------------------------------------------------------------------------
    */

    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }

    public function scopePending($query)
    {
        return $query->whereNull('completed_at');
    }
}
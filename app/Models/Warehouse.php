<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'mkdt_by',
        'mfg_by',
        'item_id',
        'purchase_order_id',
        'purchase_order_item_id',
        'job_card_id',
        'job_card_item_id',
        'status_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function mkdtBy()
    {
        return $this->belongsTo(Client::class, 'mkdt_by');
    }

    public function mfgBy()
    {
        return $this->belongsTo(Client::class, 'mfg_by');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    public function items()
    {
        return $this->hasMany(WarehouseItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */

    public function getTotalQuantityAttribute()
    {
        return $this->items()->sum('total_quantity');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes (Optional)
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status_id', 1);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'item_process_details_id',
        'purchase_order_id',
        'purchase_order_item_id',
        'dye_id',
        'designer',
        'sheet_size',
        'ups',
        'quantity',
        'final_quantity',
        'job_type',
        'urgent',
        'status_id',
        'quantity_status',
        'gsm_status',
        'ups_status',
        'job_type_status',
    ];

    /**
     * Relationships
     */

    // Each order sheet belongs to one Item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Each order sheet belongs to one Purchase Order
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    // Each order sheet belongs to one Purchase Order Item
    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    // (Optional) If you have a Dye model, link it too
    public function dye()
    {
        return $this->belongsTo(Dye::class, 'dye_id', 'id');
    }

    public function itemProcess()
    {
        return $this->belongsTo(ItemProcessDetail::class, 'item_process_details_id', 'id');
    }
}
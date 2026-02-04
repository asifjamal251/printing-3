<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'processing_id',
        'item_id',
        'item_process_details_id',
        'purchase_order_id',
        'purchase_order_item_id',
        'dye_id',
        'sheet_size',
        'ups',
        'quantity',
        'final_quantity',
        'job_type',
        'job_card_id',
        'set_number',
        'urgent',
        'status_id',
    ];

    /** 
     * 🔗 Relations 
     */

    // Belongs to a main processing batch
    public function processing()
    {
        return $this->belongsTo(Processing::class);
    }

    // Related to an item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Related to a purchase order
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    // Related to a specific purchase order item
    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }


    // Related to a specific purchase order item
    public function itemProcessDetail()
    {
        return $this->belongsTo(ItemProcessDetail::class, 'item_process_details_id', 'id');
    }

    // Related to a dye
    public function dye()
    {
        return $this->belongsTo(Dye::class);
    }
}
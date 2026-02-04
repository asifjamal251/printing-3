<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCardItem extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'coating_type_id',
        'other_coating_type_id',

        'job_card_id',
        'item_id',
        'item_process_details_id',
        'purchase_order_id',
        'purchase_order_item_id',

        'quantity',
        'ups',
        'rate',

        'item_name',
        'item_size',
        'colour',
        'gsm',
        'embossing',
        'leafing',
        'back_print',
        'braille',

        'status_id',
    ];

    /*-----------------------------------
    | Relationships
    |-----------------------------------*/

    // 🔹 Belongs to Job Card
    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function coatingType()
    {
        return $this->belongsTo(CoatingType::class);
    }

    public function otherCoatingType()
    {
        return $this->belongsTo(OtherCoatingType::class);
    }

    // 🔹 Belongs to Item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // 🔹 Belongs to Purchase Order
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    // 🔹 Belongs to Purchase Order Item
    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    // 🔹 Belongs to Item Process Detail
    public function itemProcessDetail()
    {
        return $this->belongsTo(ItemProcessDetail::class, 'item_process_details_id');
    }
}
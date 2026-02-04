<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillingItem extends Model
{
    use HasFactory;

    protected $table = 'billing_items';

    protected $fillable = [
        'item_for_billing_id',
        'item_id',
        'purchase_order_id',
        'purchase_order_item_id',
        'job_card_id',
        'job_card_item_id',
        'product_type_id',
        'coating_type_id',
        'other_coating_type_id',
        'item_name',
        'item_size',
        'colour',
        'gsm',
        'embossing',
        'leafing',
        'back_print',
        'braille',
        'artwork_code',
        'quantity_per_box',
        'number_of_box',
        'total_quantity',
    ];

    public function itemForBilling()
    {
        return $this->belongsTo(ItemForBilling::class);
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

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function coatingType()
    {
        return $this->belongsTo(CoatingType::class);
    }

    public function otherCoatingType()
    {
        return $this->belongsTo(OtherCoatingType::class);
    }
}
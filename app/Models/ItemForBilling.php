<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemForBilling extends Model
{
    use HasFactory;

    protected $table = 'item_for_billings';

    protected $fillable = [
        'mkdt_by',
        'mfg_by',
        'item_id',
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
        'purchase_order_id',
        'purchase_order_item_id',
        'job_card_id',
        'job_card_item_id',
        'quantity_per_box',
        'number_of_box',
        'total_quantity',
        'status_id',
    ];

    protected $casts = [
        'embossing'   => 'string',
        'leafing'     => 'string',
        'back_print'  => 'string',
        'braille'     => 'string',
        'status_id'   => 'integer',
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

    public function jobCardItem()
    {
        return $this->belongsTo(JobCardItem::class);
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

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }
}
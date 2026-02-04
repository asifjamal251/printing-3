<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemProcessDetail extends Model
{
    use HasFactory;
   // public $timestamps = false;

    protected $fillable = [
        'item_id',
        'coating_type_id',
        'other_coating_type_id',
        'purchase_order_id',
        'purchase_order_item_id',
        'product_type_id',
        'dye_id',
        'printing_machine_id',
        'designer',
        'job_card_id',
        'product_id',
        'batch',
        'mfg_date',
        'exp_date',
        'colour',
        'gsm',
        'embossing',
        'leafing',
        'back_print',
        'braille',
        'artwork_code',
        'job_type',
        'sheet_size',
        'number_of_sheet',
        'set_number',
        'ups',
        'board_size',
        'divide',
        'quantity',
        'rate',
        'gst_percentage',
        'amount',
        'gst_amount',
        'total_amount',
        'status_id',
        'created_at',
        'updated_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function coatingType()
    {
        return $this->belongsTo(CoatingType::class, 'coating_type_id');
    }

    public function otherCoatingType()
    {
        return $this->belongsTo(OtherCoatingType::class, 'other_coating_type_id');
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class, 'job_card_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function printingMachine()
    {
        return $this->belongsTo(PrintingMachine::class, 'printing_machine_id');
    }

    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class, 'purchase_order_item_id');
    }

    public function dye()
    {
        return $this->belongsTo(Dye::class, 'dye_id');
    }
}
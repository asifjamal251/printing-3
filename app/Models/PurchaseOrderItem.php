<?php

namespace App\Models;

use App\Models\FoilRate;
use App\Models\ItemRate;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'approved_by',
        'item_id',
        'product_type_id',
        'coating_type_id',
        'other_coating_type_id',
        'item_name',
        'item_size',
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
        'quantity',
        'rate',
        'gst_percentage',
        'amount',
        'gst_amount',
        'total_amount',
        'remarks',
        'completed_at',
        'status_id',
    ];
    // Relationships

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
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


    // protected static function boot()
    // {
    //     parent::boot();

    //     static::saving(function ($item) {
    //         $item->calculateAmounts();
    //     });

    //     static::saved(function ($item) {
    //         $item->syncItemRate();
    //     });
    // }

    // public function calculateAmounts()
    // {
    //     $quantity = (float) $this->quantity ?: 0;
    //     $rate = (float) $this->rate ?: 0;
    //     $gstPercentage = (float) $this->gst_percentage ?: 0;

    //     $amount = $quantity * $rate;
    //     $gstAmount = ($amount * $gstPercentage) / 100;
    //     $totalAmount = $amount + $gstAmount;

    //     $this->amount = number_format($amount, 2, '.', '');
    //     $this->gst_amount = number_format($gstAmount, 2, '.', '');
    //     $this->total_amount = number_format($totalAmount, 2, '.', '');
    // }

    // public function syncItemRate()
    // {
    //     ItemProcessDetail::updateOrCreate(
    //         [
    //             'item_id' => $this->item_id,
    //             'purchase_order_id' => $this->purchase_order_id,
    //             'purchase_order_item_id' => $this->id,
    //         ],
    //         [
    //             'quantity' => $this->quantity,
    //             'rate' => $this->rate,
    //             'gst_percentage' => $this->gst_percentage,
    //             'gst_amount' => $this->gst_amount,
    //             'total_amount' => $this->amount,
    //         ]
    //     );
    // }

    public function itemProcessDetail()
    {
        return $this->hasOne(ItemProcessDetail::class, 'purchase_order_item_id');
    }
}
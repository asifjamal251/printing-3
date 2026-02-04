<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialOrderItem extends Model{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'material_order_id',
        'approved_by',
        'product_id',
        'product_attribute_id',
        'unit_id',
        'quantity',
        'hsn',
        'total_weight',
        'rate',
        'gst',
        'gst_amount',
        'amount',
        'status_id',
    ];

    // Relationships

    public function materialOrder()
    {
        return $this->belongsTo(MaterialOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productAttribute()
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
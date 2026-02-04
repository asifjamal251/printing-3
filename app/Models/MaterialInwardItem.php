<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialInwardItem extends Model{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'material_inward_id',
        'material_order_id',
        'material_order_item_id',
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

    public function materialInward(){
        return $this->hasOne(MaterialInward::class,'id','material_inward_id');
    }

    public function materialOrder(){
        return $this->hasOne(MaterialOrder::class,'id','material_order_id');
    }

    public function materialOrderItem(){
        return $this->hasOne(MaterialOrderItem::class,'id','material_order_item_id');
    }

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function productAttribute()
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }


}
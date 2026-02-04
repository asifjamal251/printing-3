<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'product_id',
        'product_attribute_id',
        'godown_id',
        'opening_stock',
        'quantity',
        'in_hand_quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productAttribute(){
        return $this->belongsTo(ProductAttribute::class);
    }

    public function godown()
    {
        return $this->belongsTo(Godown::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'product_id',
        'location',
        'item_per_packet',
        'weight_per_piece',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stock(){
        return $this->hasOne(Stock::class);
    }
}

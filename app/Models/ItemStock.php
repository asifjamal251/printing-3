<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;

class ItemStock extends Model
{
    protected $fillable = [
        'item_id',
        'quantity_per_box',
        'number_of_box',
        'total_quantity',
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

    
}
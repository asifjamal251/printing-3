<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PastingItem extends Model
{
    protected $fillable = [
        'pasting_id',
        'quantity_per_box',
        'number_of_box',
        'total_quantity',
        'status_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function pasting()
    {
        return $this->belongsTo(Pasting::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Model Events (Auto Calculate total_quantity)
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::saving(function ($item) {
            $item->total_quantity =
                (int) $item->quantity_per_box * (int) $item->number_of_box;
        });
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseItem extends Model
{
    protected $fillable = [
        'warehouse_id',

        'quantity_per_box',
        'pending_number_of_box',
        'delivered_number_of_box',

        'total_quantity',
        'pending_quantity',
        'billed_quantity',

        'status_id',
    ];

    protected $casts = [
        'quantity_per_box'        => 'integer',
        'pending_number_of_box'   => 'integer',
        'delivered_number_of_box' => 'integer',
        'total_quantity'          => 'integer',
        'pending_quantity'        => 'integer',
        'billed_quantity'         => 'integer',
        'status_id'               => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Auto Calculate total_quantity
    |--------------------------------------------------------------------------
    */

    protected static function booted(){
        static::saving(function ($item) {

            $qtyPerBox = (int) ($item->quantity_per_box ?? 0);
            $pendingBox = (int) ($item->pending_number_of_box ?? 0);
            $deliveredBox = (int) ($item->delivered_number_of_box ?? 0);

            // Derived quantities
            $item->pending_quantity = $qtyPerBox * $pendingBox;
            $item->billed_quantity  = $qtyPerBox * $deliveredBox;
            $item->total_quantity   = $item->pending_quantity + $item->billed_quantity;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes (Optional)
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status_id', 1);
    }
}
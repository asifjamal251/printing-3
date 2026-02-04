<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cylinder extends Model
{
    protected $fillable = [
        'item_id',
        'cylinder_number',
        'cylinder_size',
        'colour',
        'location',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Each cylinder belongs to one item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function inwards()
    {
        return $this->hasMany(CylinderInward::class);
    }
}
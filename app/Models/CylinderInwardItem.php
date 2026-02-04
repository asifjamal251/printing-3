<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CylinderInwardItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'item_id',
        'cylinder_inward_id',
        'colour',
        'location',
        'status_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Belongs to Item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Belongs to Cylinder
    public function cylinderInward()
    {
        return $this->belongsTo(CylinderInward::class);
    }
}
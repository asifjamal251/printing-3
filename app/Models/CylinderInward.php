<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CylinderInward extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'item_id',
        'cylinder_id',
        'vendor_id',
        'client_id',
        'bill_no',
        'bill_date',
        'remarks',
        'status_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'bill_date' => 'date:d-m-Y',
    ];

    // Belongs to Item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Belongs to Cylinder
    public function cylinder()
    {
        return $this->belongsTo(Cylinder::class);
    }

    // Belongs to Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // Belongs to Client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
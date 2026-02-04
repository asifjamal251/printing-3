<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inward extends Model
{
    use HasFactory;

    protected $fillable = [
        'challan_from',
        'challan_date',
        'challan_no',
        'e_way_bill_no',
        'vehicle_no',
        'transport',
        'status_id',
        'created_by',
    ];

    protected $casts = [
        'challan_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(InwardItem::class, 'inward_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
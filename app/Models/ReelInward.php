<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReelInward extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'vendor_id',
        'receipt_no',
        'bill_date',
        'bill_number',
        'remarks',
        'status_id',
    ];

    protected $casts = [
        'bill_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(ReelInwardItem::class, 'inward_id');
    }
}
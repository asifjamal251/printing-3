<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Billing extends Model
{
    use HasFactory;

    protected $table = 'billings';

    protected $fillable = [
        'added_by',
        'bill_to',
        'ship_to',
        'firm_id',
        'bill_date',
        'bill_number',
        'invoice_number',
        'vehicle_no',
        'transporter_name',
        'status_id',
    ];

    protected $casts = [
        'bill_date' => 'date',
    ];

    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function billTo()
    {
        return $this->belongsTo(Client::class, 'bill_to');
    }

    public function shipTo()
    {
        return $this->belongsTo(Client::class, 'ship_to');
    }

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }

    public function billingItems()
    {
        return $this->hasMany(BillingItem::class);
    }
}
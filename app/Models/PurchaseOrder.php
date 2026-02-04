<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'added_by',
        'client_id',
        'po_number',
        'po_date',
        'remarks',
        'status_id',
        'completed_at',
    ];

    protected $casts = [
        'po_date' => 'date:d-m-Y',
    ];

    /**
     * Relationship: PurchaseOrder added by an Admin.
     */

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    /**
     * Relationship: PurchaseOrder belongs to a Client.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }


    /**
     * Relationship: PurchaseOrder has a Status.
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
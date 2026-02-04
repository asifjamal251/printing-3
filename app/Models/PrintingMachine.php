<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrintingMachine extends Model
{
    protected $table = 'printing_machines';

    protected $fillable = [
        'name',
        'status_id',
    ];

    /**
     * Relationship: LockType belongs to a Status.
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
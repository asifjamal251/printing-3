<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DyeLockType extends Model
{
    protected $table = 'dye_lock_types';

    protected $fillable = [
        'type',
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
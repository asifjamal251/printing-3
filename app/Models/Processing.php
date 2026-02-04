<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Processing extends Model
{
    use HasFactory;

    protected $fillable = [
        'designer',
        'added_by',
        'processing_number',
        'attachement',
        'status_id',
    ];

    /** 
     * 🔗 Relations 
     */

    public function attachement(){
        return $this->belongsToMany('App\Models\Media','attachement');
    }

    // Each processing is created by an admin
    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function adminDesigner()
    {
        return $this->belongsTo(Admin::class, 'designer');
    }

    // Each processing has many items
    public function items()
    {
        return $this->hasMany(ProcessingItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->processing_number)) {
                $latest = self::latest('id')->first();
                $nextNumber = $latest ? $latest->id + 1 : 1;
                $model->processing_number = 'PROC-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DyeDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'dye_id',
        'dye_lock_type_id',
        'length',
        'width',
        'height',
        'tuckin_flap',
        'pasting_flap',
        'ups',
    ];

    // 🔗 Relationships

    public function dye()
    {
        return $this->belongsTo(Dye::class);
    }

    public function dyeLockType()
    {
        return $this->belongsTo(DyeLockType::class);
    }

    public function getCartonSizeAttribute(){
        $parts = array_filter([$this->length, $this->width, $this->height]);
        return implode('*', $parts);
    }
}
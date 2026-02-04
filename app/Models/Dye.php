<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dye extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',      
        'dye_number',
        'sheet_size',
        'dye_type',  
        'status_id', 
    ];


    public function items()
    {
        return $this->hasMany(DyeDetail::class);
    }

    public function dyeDetails(){
        return $this->hasMany(DyeDetail::class);
    }


    public function getDyeInfoAttribute(){
        $dyeNumber = $this->dye_number ?? '';
        if ($this->dyeDetails->isEmpty()) {
            return $dyeNumber;
        }
        $details = $this->dyeDetails->map(function ($detail) {
            $cartonSize = $detail->carton_size ?? '';
            $lockType = $detail->dyeLockType->type ?? '';
            $ups = $detail->ups ?? '';
            return trim("$cartonSize - $lockType - {$ups}ups");
        })->implode(', ');

        return "{$dyeNumber} ({$details})";
    }
}
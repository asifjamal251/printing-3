<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status_badge',
        'background_colour',
        'text_colour',
    ];


    protected static function boot()
    {
        parent::boot();

        // For create
        static::creating(function ($status) {
            $status->status_badge = '<span class="badge" style="background-color:' 
                . $status->background_colour . '; color:' 
                . $status->text_colour . ';">' 
                . $status->name . '</span>';
        });

        // For update
        static::updating(function ($status) {
            $status->status_badge = '<span class="badge" style="background-color:' 
                . $status->background_colour . '; color:' 
                . $status->text_colour . ';">' 
                . $status->name . '</span>';
        });
    }
}

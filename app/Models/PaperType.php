<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mill',
        'ordering',
        'parent',
    ];

    public function children(){
        return $this->hasMany(PaperType::class, 'parent', 'id');
    }

    public function parent(){
        return $this->belongsTo(PaperType::class, 'parent');
    }
}
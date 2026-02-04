<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id', 'parent', 'name',
    ];

    public function subCategory(){
        return $this->hasOne(Category::class,'id','parent');
    }
    public function children(){
        return $this->hasMany(Category::class, 'parent', 'id');
    }

    public function parent(){
        return $this->belongsTo(Category::class, 'parent');
    }

    public function allChildrenIds(){
        $children = $this->children;
        $ids = $children->pluck('id')->toArray();
        foreach ($children as $child) {
            $ids = array_merge($ids, $child->allChildrenIds());
        }
        return $ids;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Category')
        ->setDescriptionForEvent(fn(string $eventName) => "Client has been {$eventName}")
        ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'modules';

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'name',
        'model_name',
        'status_id',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'status_id' => 'integer',
    ];

    /**
     * Scope: active modules
     */
    public function scopeActive($query)
    {
        return $query->where('status_id', 1);
    }

    /**
     * Optional: formatted name (PaperCutting → Paper Cutting)
     * Only useful if model_name or name is camel case
     */
    public function getFormattedNameAttribute()
    {
        return preg_replace('/(?<!^)([A-Z])/', ' $1', $this->name);
    }
}
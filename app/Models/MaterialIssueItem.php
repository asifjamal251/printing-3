<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialIssueItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'material_issue_items';

    protected $fillable = [
        'material_issue_id',
        'product_id',
        'product_attribute_id',
        'quantity',
        'remarks',
        'weight',
        'status_id',
    ];

    /* ================= Relationships ================= */

    public function materialIssue()
    {
        return $this->belongsTo(MaterialIssue::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productAttribute()
    {
        return $this->belongsTo(ProductAttribute::class);
    }
}
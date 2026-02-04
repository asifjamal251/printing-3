<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCardProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_attribute_id',
        'job_card_id',
        'required_sheet',
        'wastage_sheet',
        'paper_divide',
        'total_sheet',
    ];

    /**
     * Relations
     */

    // belongsTo Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // belongsTo JobCard
    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    // belongsTo Item (job card item)
    public function item()
    {
        return $this->belongsTo(JobCardItem::class, 'item_id');
    }

    // belongsTo ItemProcessDetails
    public function itemProcessDetail()
    {
        return $this->belongsTo(ItemProcessDetail::class, 'item_process_details_id');
    }
}
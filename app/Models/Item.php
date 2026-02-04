<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
     //public $timestamps = false;

    protected $fillable = [
        'mkdt_by',
        'mfg_by',
        'product_type_id',
        'coating_type_id',
        'other_coating_type_id',
        'item_name',
        'item_size',
        'colour',
        'gsm',
        'embossing',
        'leafing',
        'back_print',
        'braille',
        'artwork_code',
        'status_id',
        'created_at',
        'updated_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function itemStock()
    {
        return $this->hasOne(ItemStock::class, 'item_id', 'id');
    }

    public function itemProcessDetail()
    {
        return $this->hasOne(ItemProcessDetail::class, 'item_id', 'id')
        ->latestOfMany()->orderBy('created_at', 'desc');
    }

    public function lastItem()
    {
        return $this->hasOne(ItemProcessDetail::class, 'item_id')
        ->where('status_id', 3)
        ->orderBy('created_at', 'desc');
    }

    public function itemProcessDetails()
    {
        return $this->hasMany(ItemProcessDetail::class, 'item_id', 'id')->orderBy('created_at', 'desc');
    }

    public function coatingType()
    {
        return $this->belongsTo(CoatingType::class);
    }

    public function otherCoatingType()
    {
        return $this->belongsTo(OtherCoatingType::class);
    }

    public function mkdtBy()
    {
        return $this->belongsTo(Client::class, 'mkdt_by');
    }

    public function mfgBy()
    {
        return $this->belongsTo(Client::class, 'mfg_by');
    }

    // Each item belongs to a product type
    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function cylinderInwards()
    {
        return $this->hasMany(CylinderInward::class);
    }

    public function getItemDetailsAttribute(){
        return $this->item_name . ' ('. $this->item_size . ')';
    }

    public function getLastItemAttribute()
    {
        return $this->itemProcessDetails()
        ->where('status_id', 3)
        ->orderBy('created_at', 'desc')
        ->first();
    }
}
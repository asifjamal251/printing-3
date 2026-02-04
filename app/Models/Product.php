<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model{
    use SoftDeletes;

    protected $fillable = [
        'product_type_id',
        'category_id',
        'store_id',
        'unit_id',
        'name',
        'name_other',
        'hsn',
        'gsm',
        'gst',
        'media_id',
        'status_id',
    ];

    public function mediaFile(){
        return $this->hasOne(Media::class,'id','media_id');
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function ledgers()
    {
        return $this->hasMany(ProductLedger::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function productType(){
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

     public function getProductNameAttribute(){
        $parts = [];

        if (!empty($this->name)) {
            $parts[] = $this->name;
        }


        $fullName = implode(' / ', $parts);

        return $fullName;
    }


    public function getProductNameGSMAttribute(){
        $parts = [];

        if (!empty($this->name)) {
            $parts[] = $this->name;
        }

        $fullName = implode(' / ', $parts);

        if (!empty($this->gsm)) {
            $fullName .= ' - ' . $this->gsm;
        }

        return $fullName;
    }

    public function getFullnameAttribute(){
        $parts = [];

        if (!empty($this->name)) {
            $parts[] = $this->name;
        }


        $fullName = implode(' / ', $parts);

        $type = $this->productType->name ?? null;
        if (!empty($type)) {
            $fullName .= ' (' . $type . ')';
        }

        if (!empty($this->gsm)) {
            $fullName .= ' - ' . $this->gsm;
        }

        return $fullName;
    }
}
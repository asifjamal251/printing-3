<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_card_number',
        'job_type',
        'urgent',
        'dye_id',
        'set_number',
        'sheet_size',
        'required_sheet',
        'wastage_sheet',
        'total_sheet',
        'paper_divide',
        'attachement',
        'coating',
        'other_coating',
        'remarks',
        'tentative_date',
        'completed_date',
        'printing',
        'status_id',
    ];

    protected $casts = [
        'tentative_date' => 'date:d-m-Y',
    ];


    /*-----------------------------------
    | Relationships
    |-----------------------------------*/
    public function attachements()
    {
        return $this->belongsToMany(Media::class, 'attachements', 'job_card_id', 'media_id')
                    ->withTimestamps();
    }

    public function jobCardProducts(){
        return $this->hasMany(JobCardProduct::class);
    }

    public function firstAttachment()
    {
        return $this->attachements()->first();
    }

    public function stages()
    {
        return $this->hasMany(JobCardStage::class);
    }

    // public function attachement(){
    //     return $this->belongsToMany('App\Models\Media','attachements');
    // }


    public function getFirstAttachmentAttribute(){
        return $this->attachement?->first();
    }

    // 🔹 Each Job Card belongs to a Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // 🔹 Each Job Card belongs to a Dye
    public function dye()
    {
        return $this->belongsTo(Dye::class);
    }

    // 🔹 A Job Card has many Job Card Items
    public function items()
    {
        return $this->hasMany(JobCardItem::class);
    }



    // 🔹 (Optional) A Job Card may have related ItemProcessDetails through JobCardItems
    public function itemProcessDetails()
    {
        return $this->hasManyThrough(ItemProcessDetail::class, JobCardItem::class, 'job_card_id', 'job_card_item_id');
    }



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($job_card) {
            $prefix = 'SKG/JCN/';
            $monthYear = static::generateMonthYear(); // mm-yy
            $serialNumber = static::generateSerialNumber($monthYear, $prefix);

            $job_card->job_card_number = "{$prefix}/{$monthYear}/{$serialNumber}";
        });
    }

    protected static function generateMonthYear()
    {
        return date('m-y'); // Example: 08-25
    }

    protected static function generateSerialNumber($monthYear, $prefix)
    {
        $lastOrder = static::where('job_card_number', 'LIKE', "{$prefix}/{$monthYear}/%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastOrder) {
            $parts = explode('/', $lastOrder->job_card_number);
            $lastNumber = (int) end($parts);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return str_pad($newNumber, 4, '0', STR_PAD_LEFT); // 0001, 0002, ...
    }
}
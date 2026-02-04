<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'create_by',
        'product_type',
        'godown',
        'mill',
        'sheet_per_packet',
        'weight_per_packet',
        'name_cm',
        'name_inch',
        'hsn',
        'gsm',
        'opening_stock',
        'quantity',
        'in_hand_quantity',
        'location',
        'unit',
        'status_id',
    ];
}
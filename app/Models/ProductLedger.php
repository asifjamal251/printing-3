<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLedger extends Model
{
    protected $fillable = [
        'product_id',
        'product_attribute_id',
        'warehouse_id',
        'reference_no',
        'type',
        'old_quantity',
        'new_quantity',
        'current_quantity',
        'source_type',
        'source_id',
        'note',
        'financial_year',
        'created_by',
    ];

    public function createdBy(){
        return $this->hasOne(Admin::class,'id','created_by');
    }

    public function attribute(){
        return $this->hasOne(ProductAttribute::class,'id','product_attribute_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function godown()
    {
        return $this->belongsTo(Godown::class);
    }

    // Fetch only current financial year entries
    public function scopeCurrentFinancialYear($query)
    {
        return $query->where('financial_year', self::getCurrentFinancialYear());
    }

    // Fetch entries for a specific financial year, e.g., 2023-2024
    public function scopeForFinancialYear($query, $year)
    {
        return $query->where('financial_year', $year);
    }

    public function getSourceRouteAttribute(){
        if (!$this->source_type || !$this->source_id) {
            return null;
        }

        switch ($this->source_type) {
            case \App\Models\MaterialInward::class:
                return route('admin.material-inward.show', $this->source_id);
            case \App\Models\Issue::class:
                return route('admin.issue.show', $this->source_id);
            default:
                return null;
        }
    }

    //echo $ledger->source_type_simple;
    public function getSourceTypeSimpleAttribute(){
        return match($this->source_type) {
            \App\Models\MaterialInward::class => 'material_inward',
            \App\Models\Issue::class => 'issue',
            default => 'unknown',
        };
    }

    // ProductLedger::currentFinancialYear()->get();
    // ProductLedger::forFinancialYear('2023-2024')
    // ->where('type', 'in')
    // ->where('warehouse_id', 1)
    // ->get();


    protected static function booted()
    {
        static::creating(function ($ledger) {
            if (!$ledger->financial_year) {
                $ledger->financial_year = self::getCurrentFinancialYear();
            }
        });
    }

    public static function getCurrentFinancialYear(): string
    {
        $now = now();
        $year = $now->year;
        $nextYear = $year + 1;
        $prevYear = $year - 1;
        return $now->month >= 4
            ? "{$year}-{$nextYear}"
            : "{$prevYear}-{$year}";
    }
}

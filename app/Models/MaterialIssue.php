<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialIssue extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'material_issues';

    protected $fillable = [
        'material_issue_type',
        'create_by',
        'department_id',
        'department_user_id',
        'material_issue_number',
        'material_issue_date',
        'status_id',
        'remarks',
    ];

    protected $casts = [
        'material_issue_date' => 'date',
    ];

    /* ================= Relationships ================= */

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'create_by');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function departmentUser()
    {
        return $this->belongsTo(DepartmentUser::class);
    }

    public function items()
    {
        return $this->hasMany(MaterialIssueItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $prefix = 'ISSUE';
            $yearRange = static::generateYearRange($order->mo_date);
            $serialNumber = static::generateSerialNumber($yearRange, $prefix);
            $order->material_issue_number = "{$prefix}/{$yearRange}/{$serialNumber}";
        });
    }

    protected static function generateYearRange($date = null)
    {
        $date = $date ? \Carbon\Carbon::parse($date) : now();
        $year = $date->year;
        $month = $date->month;

        if ($month < 4) {
            $startYear = $year - 1;
            $endYear = $year;
        } else {
            $startYear = $year;
            $endYear = $year + 1;
        }

        return substr($startYear, -2) . '-' . substr($endYear, -2);
    }

    protected static function generateSerialNumber($yearRange, $prefix)
    {
        $lastOrder = static::where('material_issue_number', 'LIKE', "{$prefix}/{$yearRange}/%")
                            ->orderBy('id', 'desc')
                            ->first();

        if ($lastOrder && preg_match('/(\d+)$/', $lastOrder->material_issue_number, $matches)) {
            $lastNumber = (int) $matches[1];
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
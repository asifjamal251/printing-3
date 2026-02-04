<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'created_by',
        'bill_to',
        'ship_to',
        'order_no',
        'mo_date',
        'subtotal',
        'gst_total',
        'total',
        'remarks',
        'status_id',
        'completed_at',
    ];

   protected $casts = [
        'mo_date' => 'date:d-m-Y',
    ];

    public function items()
    {
        return $this->hasMany(MaterialOrderItem::class);
    }

    public function vendor(){
        return $this->hasOne(Vendor::class,'id','vendor_id');
    }

    public function orderBy(){
        return $this->hasOne(Admin::class,'id','created_by');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function billTo(){
        return $this->hasOne(Firm::class,'id','bill_to');
    }

    public function shipTo(){
        return $this->hasOne(Firm::class,'id','ship_to');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $prefix = 'MO';
            $yearRange = static::generateYearRange($order->mo_date);
            $serialNumber = static::generateSerialNumber($yearRange, $prefix);
            $order->order_no = "{$prefix}/{$yearRange}/{$serialNumber}";
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
        $lastOrder = static::where('order_no', 'LIKE', "{$prefix}/{$yearRange}/%")
                            ->orderBy('id', 'desc')
                            ->first();

        if ($lastOrder && preg_match('/(\d+)$/', $lastOrder->order_no, $matches)) {
            $lastNumber = (int) $matches[1];
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
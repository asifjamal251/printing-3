<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialInward extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'material_order_id',
        'bill_no',
        'vendor_id',
        'received_by',
        'bill_to',
        'bill_date',
        'receipt_no',
        'subtotal',
        'gst_total',
        'total',
        'remarks',
        'status_id',
    ];

    protected $casts = [
        'bill_date' => 'date:d-m-Y',
    ];


    public function items()
    {
        return $this->hasMany(MaterialInwardItem::class);
    }

    public function vendor(){
        return $this->hasOne(Vendor::class,'id','vendor_id');
    }

    public function receiptBy(){
        return $this->hasOne(Admin::class,'id','received_by');
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
            $prefix = 'MI';
            $yearRange = static::generateYearRange($order->mo_date);
            $serialNumber = static::generateSerialNumber($yearRange, $prefix);
            $order->receipt_no = "{$prefix}/{$yearRange}/{$serialNumber}";
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
        $lastOrder = static::where('receipt_no', 'LIKE', "{$prefix}/{$yearRange}/%")
                            ->orderBy('id', 'desc')
                            ->first();

        if ($lastOrder && preg_match('/(\d+)$/', $lastOrder->receipt_no, $matches)) {
            $lastNumber = (int) $matches[1];
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
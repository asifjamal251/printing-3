<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminIp extends Model
{
    use HasFactory;

    protected $table = 'admin_ips';

    protected $fillable = [
        'admin_id',
        'ip_address',
        'ip_location',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
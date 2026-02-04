<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    protected $fillable = [
        'name',
        'module_id',
        'admin_id',
        'status_id',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'admin_id',
        'name',
    ];

    /**
     * Department belongs to an Admin
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }


    public function users()
    {
        return $this->hasMany(DepartmentUser::class, 'department_id');
    }
}
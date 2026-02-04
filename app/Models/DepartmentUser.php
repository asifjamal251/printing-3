<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentUser extends Model
{
    use HasFactory;

    protected $table = 'department_users';

    protected $fillable = [
        'department_id',
        'name',
    ];

    /**
     * DepartmentUser belongs to a Department
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
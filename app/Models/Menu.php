<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';
    protected $primaryKey = 'slug';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['slug', 'parent', 'grand'];

    public function childs()
    {
        return $this->hasMany(Menu::class, 'parent', 'slug')->orderBy('ordering', 'asc');
    }

    // Second-level children (grandchildren)
    public function grands()
    {
        return $this->hasMany(Menu::class, 'grand', 'slug')->orderBy('ordering', 'asc');
    }

    // Permission
    public function permission()
    {
        return $this->hasOne(Permission::class, 'menu_slug', 'slug');
    }

    public function rolePermissions()
    {
        return $this->hasManyThrough(RolePermission::class, Permission::class, 'menu_slug', 'permission_key', 'slug', 'permission_key');
    }
}
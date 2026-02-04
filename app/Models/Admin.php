<?php

namespace App\Models;

use App\Models\Role;
use App\Notifications\AdminResetPasswordNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable
{
    use Notifiable, HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard = 'admin';
    protected $dates = ['date_of_birth'];
    protected $fillable = [
        'role_id',
        'name',
        'gender',
        'email',
        'mobile',
        'password',
        'plain_password',
        'media_id',
        'date_of_birth',
        'status_id',
        'google2fa_secret',
        'google2fa_enabled',
        'ip_enabled',
        'login_time_restriction_enabled',
        'login_allowed_from',
        'login_allowed_to'
    ];


    public function stores(){
        return $this->belongsToMany(Store::class, 'store_admins', 'admin_id', 'store_id')->withTimestamps();
    }
    public function media(){
        return $this->hasOne(Media::class,'id','media_id');
    }

    public function state(){
        return $this->hasOne(State::class,'id','state_id');
    }

    public function district(){
        return $this->hasOne(District::class,'id','district_id');
    }

    public function city(){
        return $this->hasOne(City::class,'id','city_id');
    }

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role(){
        return $this->hasOne(Role::class,'id','role_id');
    }
    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'role_users');
    // }
    //  public function hasAccess(string $permissions) :bool
    // {
    //     if($this->role->hasAccess($permissions)) {
    //         return true;
    //     }
    //     return false;
    // }


    public function hasAccess($permissions) :bool{
        $permissions = gettype($permissions) == 'array' ? $permissions : [$permissions];
        if($this->role->hasAccess($permissions)) {
            return true;
        }
        return false;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }

    public function ips(){
        return $this->hasMany(AdminIp::class);
    }

    protected function google2faSecret(): Attribute{

        return new Attribute(

            get: fn ($value) =>  decrypt($value),

            set: fn ($value) =>  encrypt($value),

        );

    }




}

<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class City extends Model{

  protected $fillable = [
        'name',
        'pin_code',
        'district_id',
    ];

   	public function state(){
      return $this->hasOne(State::class,'id','state_id');
    }

    public function district(){
      return $this->hasOne(District::class,'id','district_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleMake extends Model
{
    protected $table = 'vehicle_make';
    public $timestamps = false;
    protected $fillable = [
    	'name',
    	'isActive'  	
    ];

    public function model(){
    	return $this->hasMany('App\VehicleModel', 'makeId')->where('isActive',1);
    }
}

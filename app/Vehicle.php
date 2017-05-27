<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'vehicle';
    public $timestamps = false;
    protected $fillable = [
        'plate',
    	'modelId',
        'mileage', 	
    ];

    public function model(){
    	return $this->belongsTo('App\VehicleModel','modelId')->where('isActive',1);
    }
}

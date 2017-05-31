<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    protected $table = 'vehicle_model';
    public $timestamps = false;
    protected $fillable = [
        'makeId',
    	'name',
        'year',
        'transmission',
    	'isActive'
    ];

    public function make(){
    	return $this->belongsTo('App\VehicleMake','makeId');
    }
}

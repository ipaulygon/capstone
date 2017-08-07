<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductVehicle extends Model
{
    protected $table = 'product_vehicle';
    public $timestamps = false;
    protected $fillable = [
    	'productId',
    	'modelId',
        'isManual',
        'isActive'	
    ];

    public function product(){
        return $this->belongsTo('App\Product','productId');
    }

    public function model(){
        return $this->belongsTo('App\VehicleModel','modelId');
    }
}

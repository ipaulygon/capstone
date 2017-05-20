<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    public $timestamps = false;
    protected $fillable = [
    	'name',
    	'description',
        'price',
        'reorder',
    	'typeId',
    	'brandId',
        'varianceId',
        'isOriginal',
    	'isActive'  	
    ];

    public function type(){
        return $this->belongsTo('App\ProductType','typeId')->where('isActive',1);
    }

    public function brand(){
        return $this->belongsTo('App\ProductBrand','brandId')->where('isActive',1);
    }

    public function variance(){
        return $this->belongsTo('App\ProductVariance','varianceId')->where('isActive',1);
    }

    public function vehicle(){
        return $this->hasMany('App\ProductVehicle', 'productId')->where('isActive',1);
    }
}

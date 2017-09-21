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
        'isWarranty',
        'year',
        'month',
        'day',
    	'isActive'  	
    ];

    public function type(){
        return $this->belongsTo('App\ProductType','typeId');
    }

    public function brand(){
        return $this->belongsTo('App\ProductBrand','brandId');
    }

    public function variance(){
        return $this->belongsTo('App\ProductVariance','varianceId');
    }

    public function vehicle(){
        return $this->hasMany('App\ProductVehicle', 'productId')->where('isActive',1);
    }

    public function discount(){
        return $this->hasOne('App\DiscountProduct', 'productId')->where('isActive',1);
    }
    
    public function discountRecord(){
        return $this->hasMany('App\DiscountProduct', 'productId');
    }

    public function priceRecord(){
        return $this->hasMany('App\ProductPrice', 'productId');
    }

    public function inventory(){
        return $this->hasOne('App\Inventory', 'productId');
    }
}

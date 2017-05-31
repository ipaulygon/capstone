<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'service';
    public $timestamps = false;
    protected $fillable = [
    	'name',
        'price',
        'size',
    	'categoryId',
    	'isActive'  	
    ];

    public function category(){
        return $this->belongsTo('App\ServiceCategory','categoryId');
    }

    public function discount(){
        return $this->hasOne('App\DiscountService', 'serviceId')->where('isActive',1);
    }

    public function discountRecord(){
        return $this->hasMany('App\DiscountService', 'serviceId');
    }

    public function priceRecord(){
        return $this->hasMany('App\ServicePrice', 'serviceId');
    }
}

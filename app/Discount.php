<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discount';
    public $timestamps = false;
    protected $fillable = [
    	'name',
    	'rate',
        'type',
    	'isActive'  	
    ];

    public function product(){
    	return $this->hasMany('App\DiscountProduct', 'discountId')->where('isActive',1);
    }

    public function service(){
    	return $this->hasMany('App\DiscountService', 'discountId')->where('isActive',1);
    }

    public function rateRecord(){
        return $this->hasMany('App\DiscountRate', 'discountId');
    }
}

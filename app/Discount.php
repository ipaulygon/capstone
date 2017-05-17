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
    	'isActive'  	
    ];

    public function product(){
    	return $this->hasMany('App\DiscountProduct', 'discountId');
    }

    public function service(){
    	return $this->hasMany('App\DiscountService', 'discountId');
    }
}

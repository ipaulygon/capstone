<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountProduct extends Model
{
    protected $table = 'discount_product';
    public $incrementing = false;
    protected $fillable = [
    	'discountId',
    	'productId',
        'isActive'
    ];

    public function product(){
        return $this->belongsTo('App\Product','productId')->where('isActive',1);
    }

    public function discount(){
        return $this->belongsTo('App\Discount','discountId')->where('isActive',1);
    }
}
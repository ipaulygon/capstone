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
        return $this->belongsTo('App\Product','productId');
    }

    public function header(){
        return $this->belongsTo('App\Discount','discountId');
    }
}

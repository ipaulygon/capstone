<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromoProduct extends Model
{
    protected $table = 'promo_product';
    public $timestamps = false;
    protected $fillable = [
    	'promoId',
    	'productId',
        'quantity',
        'freeQuantity',
    	'isActive'  	
    ];

    public function product(){
        return $this->belongsTo('App\Product','productId');
    }

    public function promo(){
        return $this->belongsTo('App\Promo','promoId');
    }
}

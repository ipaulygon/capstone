<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $table = 'promo';
    public $timestamps = false;
    protected $fillable = [
    	'name',
        'price',
        'dateStart',
        'dateEnd',
        'stock',
    	'isActive'  	
    ];

    public function freeProduct(){
        return $this->hasMany('App\PromoProduct', 'promoId')->where('isActive',1)->where('isFree',1);
    }

    public function product(){
        return $this->hasMany('App\PromoProduct', 'promoId')->where('isActive',1)->where('isFree',0);
    }

    public function freeService(){
        return $this->hasMany('App\PromoService', 'promoId')->where('isActive',1)->where('isFree',1);
    }
    
    public function service(){
        return $this->hasMany('App\PromoService', 'promoId')->where('isActive',1)->where('isFree',0);
    }

    public function priceRecord(){
        return $this->hasMany('App\PromoPrice', 'promoId');
    }
}

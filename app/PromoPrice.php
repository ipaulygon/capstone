<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromoPrice extends Model
{
    protected $table = 'promo_price';
    public $incrementing = false;
    protected $fillable = [
    	'promoId',
    	'price'	
    ];

    public function promo(){
        return $this->belongsTo('App\Promo','promoId')->where('isActive',1);
    }
}

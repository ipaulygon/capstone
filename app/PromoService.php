<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromoService extends Model
{
    protected $table = 'promo_service';
    public $timestamps = false;
    protected $fillable = [
    	'promoId',
    	'serviceId',
        'isFree',
    	'isActive'  	
    ];

    public function service(){
        return $this->belongsTo('App\Service','serviceId');
    }

    public function promo(){
        return $this->belongsTo('App\Promo','promoId');
    }
}

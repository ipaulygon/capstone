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
        return $this->belongsTo('App\Service','serviceId')->where('isActive',1);
    }

    public function promo(){
        return $this->belongsTo('App\Promo','promoId')->where('isActive',1);
    }
}

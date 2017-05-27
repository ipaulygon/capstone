<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstimatePromo extends Model
{
    protected $table = 'estimate_promo';
    public $timestamps = false;
    protected $fillable = [
    	'estimateId',
    	'promoId',
        'quantity',
    	'isActive'  	
    ];

    public function promo(){
        return $this->belongsTo('App\Promo','promoId')->where('isActive',1);
    }

    public function header(){
        return $this->belongsTo('App\EstimateHeader','estimateId');
    }
}

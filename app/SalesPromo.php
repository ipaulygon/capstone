<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesPromo extends Model
{
    protected $table = 'sales_promo';
    public $timestamps = false;
    protected $fillable = [
    	'salesId',
    	'promoId',
        'quantity',
    	'isActive',
    ];

    public function promo(){
        return $this->belongsTo('App\Promo','promoId');
    }

    public function header(){
        return $this->belongsTo('App\SalesHeader','salesId');
    }
}

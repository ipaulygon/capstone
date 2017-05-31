<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicePrice extends Model
{
    protected $table = 'service_price';
    public $incrementing = false;
    protected $fillable = [
    	'serviceId',
    	'price'	
    ];

   public function service(){
        return $this->belongsTo('App\Service','serviceId');
    }
}

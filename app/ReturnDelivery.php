<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReturnDelivery extends Model
{
    protected $table = 'return_delivery';
    protected $fillable = [
        'returnId',
    	'deliveryId',
    ];

    public function return(){
    	return $this->belongsTo('App\ReturnHeader', 'returnId');
    }

    public function delivery(){
    	return $this->belongsTo('App\DeliveryHeader', 'deliveryId');
    }
}

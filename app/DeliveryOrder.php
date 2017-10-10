<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    protected $table = 'delivery_order';
    protected $fillable = [
        'deliveryId',
    	'purchaseId',
    ];

    public function delivery(){
    	return $this->belongsTo('App\DeliverHeader', 'deliveryId');
    }

    public function purchase(){
    	return $this->belongsTo('App\PurchaseHeader', 'purchaseId');
    }
}

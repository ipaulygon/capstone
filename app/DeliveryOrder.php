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
}

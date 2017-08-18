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
}

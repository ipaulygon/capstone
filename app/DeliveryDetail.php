<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryDetail extends Model
{
    protected $table = 'delivery_detail';
    protected $fillable = [
    	'deliveryId',
        'productId',
        'quantity',
        'isActive'
    ];

    public function header(){
    	return $this->belongsTo('App\DeliveryHeader', 'deliveryId');
    }
    
    public function product(){
    	return $this->belongsTo('App\Product', 'productId');
    }
}

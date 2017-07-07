<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryHeader extends Model
{
    protected $table = 'delivery_header';
    public $incrementing = false;
    protected $fillable = [
        'id',
    	'supplierId',
        'dateMake'
    ];

    public function detail(){
    	return $this->hasMany('App\DeliveryDetail', 'deliveryId');
    }

    public function order(){
    	return $this->hasMany('App\DeliveryOrder', 'deliveryId');
    }
    
    public function supplier(){
    	return $this->belongsTo('App\Supplier', 'supplierId');
    }
}

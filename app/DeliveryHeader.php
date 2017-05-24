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
    ];

    public function detail(){
    	return $this->hasMany('App\DeliveryDetail', 'deliveryId')->where('isActive',1);
    }
    
    public function supplier(){
    	return $this->belongsTo('App\Supplier', 'supplierId');
    }
}

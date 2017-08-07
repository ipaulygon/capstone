<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $table = 'purchase_detail';
    protected $fillable = [
    	'purchaseId',
        'productId',
        'modelId',
        'isManual',
        'quantity',
        'delivered',
        'price',
    	'isActive'  	
    ];

    public function header(){
    	return $this->belongsTo('App\PurchaseHeader', 'purchaseId');
    }
    
    public function product(){
    	return $this->belongsTo('App\Product', 'productId');
    }

    public function vehicle(){
    	return $this->belongsTo('App\VehicleModel', 'modelId');
    }
}

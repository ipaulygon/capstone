<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseHeader extends Model
{
    protected $table = 'purchase_header';
    public $incrementing = false;
    protected $fillable = [
        'id',
    	'supplierId',
        'remarks',
    	'isActive' ,
        'isFinalize',
        'isDelivered'	
    ];

    public function detail(){
    	return $this->hasMany('App\PurchaseDetail', 'purchaseId')->where('isActive',1);
    }
    
    public function supplier(){
    	return $this->belongsTo('App\Supplier', 'supplierId');
    }
}

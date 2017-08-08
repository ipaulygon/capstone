<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReturnDetail extends Model
{
    protected $table = 'return_detail';
    protected $fillable = [
    	'returnId',
        'productId',
        'deliveryId',
        'quantity',
        'isActive'
    ];

    public function header(){
    	return $this->belongsTo('App\ReturnHeader', 'returnId');
    }
    
    public function product(){
    	return $this->belongsTo('App\Product', 'productId');
    }
}

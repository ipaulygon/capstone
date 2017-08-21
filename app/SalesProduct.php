<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesProduct extends Model
{
    protected $table = 'sales_product';
    public $timestamps = false;
    protected $fillable = [
    	'salesId',
    	'productId',
        'quantity',
    	'isActive',
        'isVoid'
    ];

    public function product(){
        return $this->belongsTo('App\Product','productId');
    }

    public function header(){
        return $this->belongsTo('App\SalesHeader','salesId');
    }
}

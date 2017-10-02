<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarrantySalesProduct extends Model
{
    protected $table = 'warranty_sales_product';
    protected $fillable = [
    	'warrantyId',
        'salesProductId',
        'productId',
        'quantity',
    ];

    public function product(){
        return $this->belongsTo('App\Product','productId');
    }

    public function sales(){
        return $this->belongsTo('App\SalesProduct','salesProductId');
    }

    public function header(){
        return $this->belongsTo('App\WarrantySalesHeader','warrantyId');
    }
}

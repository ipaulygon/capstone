<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarrantySalesPromo extends Model
{
    protected $table = 'warranty_sales_promo';
    protected $fillable = [
    	'warrantyId',
        'salesPromoId',
        'productId',
        'quantity',
    ];

    public function product(){
        return $this->belongsTo('App\Product','productId');
    }

    public function sales(){
        return $this->belongsTo('App\SalesPromo','salesPromoId');
    }

    public function header(){
        return $this->belongsTo('App\WarrantySalesHeader','warrantyId');
    }
}

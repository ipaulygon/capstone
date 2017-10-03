<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarrantySalesHeader extends Model
{
    protected $table = 'warranty_sales_header';
    protected $fillable = [
    	'salesId',
    ];

    public function product(){
        return $this->hasMany('App\WarrantySalesProduct','warrantyId');
    }

    public function package(){
        return $this->hasMany('App\WarrantySalesPackage','warrantyId');
    }

    public function promo(){
        return $this->hasMany('App\WarrantySalesPromo','warrantyId');
    }

    public function sales(){
        return $this->belongsTo('App\SalesHeader','salesId');
    }
}

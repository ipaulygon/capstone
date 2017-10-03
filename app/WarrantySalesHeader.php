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
        return $this->hasMany('App\WarrantySalesProduct','salesId');
    }

    public function package(){
        return $this->hasMany('App\WarrantySalesPackage','salesId');
    }

    public function promo(){
        return $this->hasMany('App\WarrantySalesPromo','salesId');
    }

    public function sales(){
        return $this->belongsTo('App\SalesHeader','salesId');
    }
}

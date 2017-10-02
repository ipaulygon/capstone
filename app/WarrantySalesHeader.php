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
        return $this->hasMany('App\SalesProduct','salesId')->where('isActive',1);
    }

    public function package(){
        return $this->hasMany('App\SalesPackage','salesId')->where('isActive',1);
    }

    public function promo(){
        return $this->hasMany('App\SalesPromo','salesId')->where('isActive',1);
    }

    public function sales(){
        return $this->belongsTo('App\SalesHeader','salesId');
    }
}

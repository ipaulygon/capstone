<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesHeader extends Model
{
    protected $table = 'sales_header';
    protected $fillable = [
    	'customerId',
        'total'
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
    
    public function discount(){
        return $this->hasOne('App\SalesDiscount','salesId')->where('isActive',1);
    }

    public function customer(){
        return $this->belongsTo('App\Customer','customerId');
    }
}

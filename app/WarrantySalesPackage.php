<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarrantySalesPackage extends Model
{
    protected $table = 'warranty_sales_package';
    protected $fillable = [
    	'warrantyId',
        'salesPackageId',
        'productId',
        'quantity',
    ];

    public function product(){
        return $this->belongsTo('App\Product','productId');
    }

    public function sales(){
        return $this->belongsTo('App\SalesPackage','salesPackageId');
    }

    public function header(){
        return $this->belongsTo('App\WarrantySalesHeader','warrantyId');
    }
}

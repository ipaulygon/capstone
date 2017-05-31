<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageProduct extends Model
{
    protected $table = 'package_product';
    public $timestamps = false;
    protected $fillable = [
    	'packageId',
    	'productId',
        'quantity',
    	'isActive'  	
    ];

    public function product(){
        return $this->belongsTo('App\Product','productId');
    }

    public function package(){
        return $this->belongsTo('App\Package','packageId');
    }
}

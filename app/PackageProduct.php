<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageProduct extends Model
{
    protected $table = 'package_product';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
    	'packageId',
    	'productId',
        'quantity',
    	'isActive'  	
    ];

    public function product(){
        return $this->belongsTo('App\Product','productId')->where('isActive',1);
    }

    public function package(){
        return $this->belongsTo('App\Package','packageId')->where('isActive',1);
    }
}

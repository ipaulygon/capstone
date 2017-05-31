<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'package';
    public $timestamps = false;
    protected $fillable = [
    	'name',
        'price',
    	'isActive'  	
    ];

    public function product(){
        return $this->hasMany('App\PackageProduct', 'packageId')->where('isActive',1);
    }
    
    public function service(){
        return $this->hasMany('App\PackageService', 'packageId')->where('isActive',1);
    }
    
    public function priceRecord(){
        return $this->hasMany('App\PackagePrice', 'packageId');
    }
}

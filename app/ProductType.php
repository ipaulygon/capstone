<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $table = 'product_type';
    public $timestamps = false;
    protected $fillable = [
    	'name',
    	'isActive'  	
    ];

    public function tb(){
    	return $this->hasMany('App\TypeBrand', 'typeId');
    }

    public function tv(){
    	return $this->hasMany('App\TypeVariance', 'typeId');
    }

    public function product(){
        return $this->hasMany('App\Product', 'typeId')->where('isActive',1);
    }
}

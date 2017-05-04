<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model
{
    protected $table = 'product_brand';
    public $timestamps = false;
    protected $fillable = [
    	'name',
    	'isActive'  	
    ];

    public function tb(){
    	return $this->hasMany('App\TypeBrand', 'brandId');
    }
}

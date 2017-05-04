<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductVariance extends Model
{
    protected $table = 'product_variance';
    public $timestamps = false;
    protected $fillable = [
    	'name',
    	'size',
    	'units',
        'isOriginal',
    	'isActive'  	
    ];

    public function product(){
    	return $this->hasMany('App\Product', 'varianceId')->where('isActive',1);
    }

    public function tv(){
        return $this->hasMany('App\TypeVariance', 'varianceId');
    }

}

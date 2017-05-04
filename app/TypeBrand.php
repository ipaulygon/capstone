<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeBrand extends Model
{
    protected $table = 'type_brand';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
    	'typeId',
    	'brandId'  	
    ];

    public function type(){
        return $this->belongsTo('App\ProductType','typeId')->where('isActive',1);
    }

    public function brand(){
        return $this->belongsTo('App\ProductBrand','brandId')->where('isActive',1);
    }
}

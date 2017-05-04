<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeVariance extends Model
{
    protected $table = 'type_variance';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
    	'typeId',
    	'varianceId'  	
    ];

    public function type(){
        return $this->belongsTo('App\ProductType','typeId')->where('isActive',1);
    }

    public function variance(){
        return $this->belongsTo('App\ProductVariance','varianceId')->where('isActive',1);
    }
}

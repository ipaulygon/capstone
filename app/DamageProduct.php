<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DamageProduct extends Model
{
    protected $table = 'damage_product';
    protected $fillable = [
        'productId',
        'quantity',
        'remarks'
    ];

    public function product(){
    	return $this->belongsTo('App\Product', 'productId');
    }
}

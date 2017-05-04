<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    protected $table = 'product_price';
    public $incrementing = false;
    protected $fillable = [
    	'productId',
    	'price'	
    ];

    public function product(){
        return $this->belongsTo('App\Product','productId')->where('isActive',1);
    }
}

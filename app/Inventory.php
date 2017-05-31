<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';
    protected $fillable = [
    	'productId',
    	'quantity'	
    ];

   public function product(){
        return $this->belongsTo('App\Product','productId');
    }
}

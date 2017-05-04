<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';
    public $incrementing = false;
    protected $fillable = [
    	'productId',
    	'quantity'	
    ];

   public function product(){
        return $this->belongsTo('App\Product','productId')->where('isActive',1);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesDiscount extends Model
{
    protected $table = 'sales_discount';
    public $timestamps = false;
    protected $fillable = [
    	'salesId',
    	'discountId',
    	'isActive'  	
    ];

    public function discount(){
        return $this->belongsTo('App\Discount','discountId');
    }

    public function header(){
        return $this->belongsTo('App\SalesHeader','salesId');
    }
}

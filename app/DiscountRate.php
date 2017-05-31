<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountRate extends Model
{
    protected $table = 'discount_rate';
    public $incrementing = false;
    protected $fillable = [
    	'discountId',
    	'rate'	
    ];

    public function discount(){
        return $this->belongsTo('App\Discount','discountId');
    }
}

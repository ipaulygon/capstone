<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstimateDiscount extends Model
{
    protected $table = 'estimate_discount';
    public $timestamps = false;
    protected $fillable = [
    	'estimateId',
    	'discountId',
    	'isActive'  	
    ];

    public function discount(){
        return $this->belongsTo('App\Discount','discountId');
    }

    public function header(){
        return $this->belongsTo('App\EstimateHeader','estimateId');
    }
}

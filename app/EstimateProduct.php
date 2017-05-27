<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstimateProduct extends Model
{
    protected $table = 'estimate_product';
    public $timestamps = false;
    protected $fillable = [
    	'estimateId',
    	'productId',
        'quantity',
    	'isActive'  	
    ];

    public function product(){
        return $this->belongsTo('App\Product','productId')->where('isActive',1);
    }

    public function header(){
        return $this->belongsTo('App\EstimateHeader','estimateId');
    }
}

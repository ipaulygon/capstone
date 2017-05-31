<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstimatePackage extends Model
{
    protected $table = 'estimate_package';
    public $timestamps = false;
    protected $fillable = [
    	'estimateId',
    	'packageId',
        'quantity',
    	'isActive'  	
    ];

    public function package(){
        return $this->belongsTo('App\Package','packageId');
    }

    public function header(){
        return $this->belongsTo('App\EstimateHeader','estimateId');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstimateService extends Model
{
    protected $table = 'estimate_service';
    public $timestamps = false;
    protected $fillable = [
    	'estimateId',
    	'serviceId',
    	'isActive'  	
    ];

    public function service(){
        return $this->belongsTo('App\Service','serviceId')->where('isActive',1);
    }

    public function header(){
        return $this->belongsTo('App\EstimateHeader','estimateId');
    }
}

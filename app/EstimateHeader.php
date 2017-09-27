<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstimateHeader extends Model
{
    protected $table = 'estimate_header';
    protected $fillable = [
        'jobId',
    	'customerId',
        'vehicleId',
        'rackId',
    	'isFinalize',
    ];

    public function product(){
        return $this->hasMany('App\EstimateProduct','estimateId')->where('isActive',1);
    }

    public function service(){
        return $this->hasMany('App\EstimateService','estimateId')->where('isActive',1);
    }

    public function package(){
        return $this->hasMany('App\EstimatePackage','estimateId')->where('isActive',1);
    }

    public function promo(){
        return $this->hasMany('App\EstimatePromo','estimateId')->where('isActive',1);
    }
    
    public function discount(){
        return $this->hasOne('App\EstimateDiscount','estimateId')->where('isActive',1);
    }

    public function customer(){
        return $this->belongsTo('App\Customer','customerId');
    }
    
    public function vehicle(){
        return $this->belongsTo('App\Vehicle','vehicleId');
    }

    public function technician(){
        return $this->hasMany('App\EstimateTechnician','estimateId')->where('isActive',1);
    }
}

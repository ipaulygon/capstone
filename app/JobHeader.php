<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\JobPayment;

class JobHeader extends Model
{
    protected $table = 'job_header';
    protected $fillable = [
    	'customerId',
        'vehicleId',
        'rackId',
    	'isFinalize',
        'isComplete',
        'total',
        'paid',
        'start',
        'end',
        'release',
        'remarks',
        'isVoid'
    ];

    public function product(){
        return $this->hasMany('App\JobProduct','jobId')->where('isActive',1);
    }

    public function service(){
        return $this->hasMany('App\JobService','jobId')->where('isActive',1);
    }

    public function package(){
        return $this->hasMany('App\JobPackage','jobId')->where('isActive',1);
    }

    public function promo(){
        return $this->hasMany('App\JobPromo','jobId')->where('isActive',1);
    }
    
    public function discount(){
        return $this->hasOne('App\JobDiscount','jobId')->where('isActive',1);
    }

    public function customer(){
        return $this->belongsTo('App\Customer','customerId');
    }
    
    public function vehicle(){
        return $this->belongsTo('App\Vehicle','vehicleId');
    }

    public function technician(){
        return $this->hasMany('App\JobTechnician','jobId')->where('isActive',1);
    }

    public function payment(){
        return $this->hasMany('App\JobPayment','jobId');
    }

    public function refund(){
        return $this->hasMany('App\JobRefund','jobId');
    }

    public function rack(){
        return $this->belongsTo('App\Rack', 'rackId');
    }
}

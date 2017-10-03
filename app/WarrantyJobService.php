<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarrantyJobService extends Model
{
    protected $table = 'warranty_job_service';
    protected $fillable = [
    	'warrantyId',
        'jobServiceId',
        'serviceId',
    ];

    public function service(){
        return $this->belongsTo('App\Service','serviceId');
    }

    public function job(){
        return $this->belongsTo('App\JobService','jobServiceId');
    }

    public function header(){
        return $this->belongsTo('App\WarrantyJobHeader','warrantyId');
    }
}

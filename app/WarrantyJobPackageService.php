<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarrantyJobPackageService extends Model
{
    protected $table = 'warranty_job_package_service';
    protected $fillable = [
    	'warrantyId',
        'jobPackageId',
        'serviceId',
    ];

    public function service(){
        return $this->belongsTo('App\Service','serviceId');
    }

    public function job(){
        return $this->belongsTo('App\JobPackage','jobPackageId');
    }

    public function header(){
        return $this->belongsTo('App\WarrantyJobHeader','warrantyId');
    }
}

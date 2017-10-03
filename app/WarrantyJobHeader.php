<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarrantyJobHeader extends Model
{
    protected $table = 'warranty_job_header';
    protected $fillable = [
        'jobId',
        'warrantyJobId'
    ];

    public function product(){
        return $this->hasMany('App\WarrantyJobProduct','jobId');
    }
    
    public function service(){
        return $this->hasMany('App\WarrantyJobService','jobId');
    }

    public function package(){
        return $this->hasMany('App\WarrantyJobPackage','jobId');
    }

    public function promo(){
        return $this->hasMany('App\WarrantyJobPromo','jobId');
    }

    public function job(){
        return $this->belongsTo('App\JobHeader','jobId');
    }
}

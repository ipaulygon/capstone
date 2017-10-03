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
        return $this->hasMany('App\WarrantyJobProduct','warrantyId');
    }
    
    public function service(){
        return $this->hasMany('App\WarrantyJobService','warrantyId');
    }

    public function packageProduct(){
        return $this->hasMany('App\WarrantyJobPackageProduct','warrantyId');
    }
    
    public function packageService(){
        return $this->hasMany('App\WarrantyJobPackageService','warrantyId');
    }

    public function promoProduct(){
        return $this->hasMany('App\WarrantyJobPromoProduct','warrantyId');
    }
    
    public function promoService(){
        return $this->hasMany('App\WarrantyJobPromoService','warrantyId');
    }

    public function job(){
        return $this->belongsTo('App\JobHeader','jobId');
    }
}

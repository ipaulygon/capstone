<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarrantyJobPackageProduct extends Model
{
    protected $table = 'warranty_job_package_product';
    protected $fillable = [
    	'warrantyId',
        'jobPackageId',
        'productId',
        'quantity',
    ];

    public function product(){
        return $this->belongsTo('App\Product','productId');
    }

    public function job(){
        return $this->belongsTo('App\JobPackage','jobPackageId');
    }

    public function header(){
        return $this->belongsTo('App\WarrantyJobHeader','warrantyId');
    }
}

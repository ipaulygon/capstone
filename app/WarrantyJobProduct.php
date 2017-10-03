<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarrantyJobProduct extends Model
{
    protected $table = 'warranty_job_product';
    protected $fillable = [
    	'warrantyId',
        'jobProductId',
        'productId',
        'quantity',
    ];

    public function product(){
        return $this->belongsTo('App\Product','productId');
    }

    public function job(){
        return $this->belongsTo('App\JobProduct','jobProductId');
    }

    public function header(){
        return $this->belongsTo('App\WarrantyJobHeader','warrantyId');
    }
}

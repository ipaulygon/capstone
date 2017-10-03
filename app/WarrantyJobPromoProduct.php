<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarrantyJobPromoProduct extends Model
{
    protected $table = 'warranty_job_promo_product';
    protected $fillable = [
    	'warrantyId',
        'jobPromoId',
        'productId',
        'quantity',
    ];

    public function product(){
        return $this->belongsTo('App\Product','productId');
    }

    public function job(){
        return $this->belongsTo('App\JobPromo','jobPromoId');
    }

    public function header(){
        return $this->belongsTo('App\WarrantyJobHeader','warrantyId');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarrantyJobPromoService extends Model
{
    protected $table = 'warranty_job_promo_service';
    protected $fillable = [
    	'warrantyId',
        'jobPromoId',
        'serviceId',
    ];

    public function service(){
        return $this->belongsTo('App\Service','serviceId');
    }

    public function job(){
        return $this->belongsTo('App\JobPromo','jobPromoId');
    }

    public function header(){
        return $this->belongsTo('App\WarrantyJobHeader','warrantyId');
    }
}

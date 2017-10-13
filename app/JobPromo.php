<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobPromo extends Model
{
    protected $table = 'job_promo';
    protected $fillable = [
    	'jobId',
    	'promoId',
        'quantity',
        'completed',
    	'isActive',
        'isComplete',
        'isVoid'	
    ];

    public function promo(){
        return $this->belongsTo('App\Promo','promoId');
    }

    public function header(){
        return $this->belongsTo('App\JobHeader','jobId');
    }
}

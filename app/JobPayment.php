<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobPayment extends Model
{
    protected $table = 'job_payment';
    protected $fillable = [
    	'jobId',
        'paid',  	
    ];

    public function header(){
        return $this->belongsTo('App\JobHeader','jobId');
    }
}

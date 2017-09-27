<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobRefund extends Model
{
    protected $table = 'job_refund';
    protected $fillable = [
    	'jobId',
        'refund'
    ];

    public function header(){
        return $this->belongsTo('App\JobHeader','jobId');
    }
}

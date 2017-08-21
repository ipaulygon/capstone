<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobService extends Model
{
    protected $table = 'job_service';
    public $timestamps = false;
    protected $fillable = [
    	'jobId',
    	'serviceId',
    	'isActive',
        'isComplete',
        'isVoid'
    ];

    public function service(){
        return $this->belongsTo('App\Service','serviceId');
    }

    public function header(){
        return $this->belongsTo('App\JobHeader','jobId');
    }
}

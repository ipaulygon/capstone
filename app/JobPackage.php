<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobPackage extends Model
{
    protected $table = 'job_package';
    protected $fillable = [
    	'jobId',
    	'packageId',
        'quantity',
        'completed',
    	'isActive',
        'isComplete',
        'isVoid',
    ];

    public function package(){
        return $this->belongsTo('App\Package','packageId');
    }

    public function header(){
        return $this->belongsTo('App\JobHeader','jobId');
    }
}

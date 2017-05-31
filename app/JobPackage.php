<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobPackage extends Model
{
    protected $table = 'job_package';
    public $timestamps = false;
    protected $fillable = [
    	'jobId',
    	'packageId',
        'quantity',
    	'isActive'  	
    ];

    public function package(){
        return $this->belongsTo('App\Package','packageId');
    }

    public function header(){
        return $this->belongsTo('App\JobHeader','jobId');
    }
}

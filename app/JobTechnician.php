<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobTechnician extends Model
{
    protected $table = 'job_technician';
    protected $fillable = [
    	'jobId',
        'technicianId',
    	'isActive',
    ];

    public function header(){
        return $this->belongsTo('App\JobHeader','jobId');
    }
    
    public function technician(){
        return $this->belongsTo('App\Technician','technicianId');
    }
}

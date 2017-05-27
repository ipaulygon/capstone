<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InspectionTechnician extends Model
{
    protected $table = 'inspection_technician';
    protected $fillable = [
    	'inspectionId',
        'technicianId',
    	'isActive',
    ];

    public function header(){
        return $this->belongsTo('App\InspectionHeader','inspectionId');
    }
    
    public function technician(){
        return $this->belongsTo('App\Technician','technicianId');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InspectionHeader extends Model
{
    protected $table = 'inspection_header';
    protected $fillable = [
    	'customerId',
        'vehicleId',
        'rackId',
    	'remarks',
    ];

    public function detail(){
        return $this->hasMany('App\InspectionDetail','inspectionId')->where('isActive',1);
    }
    
    public function customer(){
        return $this->belongsTo('App\Customer','customerId');
    }
    
    public function vehicle(){
        return $this->belongsTo('App\Vehicle','vehicleId');
    }
    
    public function technician(){
        return $this->hasMany('App\InspectionTechnician','inspectionId')->where('isActive',1);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstimateTechnician extends Model
{
    protected $table = 'estimate_technician';
    protected $fillable = [
    	'estimateId',
        'technicianId',
    	'isActive',
    ];

    public function header(){
        return $this->belongsTo('App\EstimateHeader','estimateId');
    }
    
    public function technician(){
        return $this->belongsTo('App\Technician','technicianId');
    }
}

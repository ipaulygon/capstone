<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TechnicianSkill extends Model
{
    protected $table = 'technician_skill';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
    	'technicianId',
    	'categoryId'  	
    ];

    public function technician(){
        return $this->belongsTo('App\Technician','technicianId');
    }

    public function category(){
        return $this->belongsTo('App\ServiceCategory','categoryId');
    }
}

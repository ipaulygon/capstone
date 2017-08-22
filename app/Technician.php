<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    protected $table = 'technician';
    public $timestamps = false;
    protected $fillable = [
    	'firstName',
    	'middleName',
        'lastName',
        'street',
        'brgy',
        'city',
        'birthdate',
        'contact',
        'email',
        'image',
        'username',
        'password',
    	'isActive'  	
    ];

    public function skill(){
        return $this->hasMany('App\TechnicianSkill','technicianId');
    }

    public function job(){
        return $this->hasMany('App\JobTechnician','technicianId');
    }
}

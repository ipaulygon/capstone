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
        'address',
        'birthdate',
        'contact',
        'email',
        'image',
    	'isActive'  	
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    public $timestamps = false;
    protected $fillable = [
    	'name',
    	'street',
        'brgy',
        'city',
    	'isActive'  	
    ];

    public function person(){
    	return $this->hasMany('App\SupplierPerson', 'spId');
    }

    public function number(){
    	return $this->hasMany('App\SupplierContact', 'scId');
    }
}

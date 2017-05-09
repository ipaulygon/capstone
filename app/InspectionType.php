<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InspectionType extends Model
{
    protected $table = 'inspection_type';
    public $timestamps = false;
    protected $fillable = [
    	'name',
    	'isActive'  	
    ];

    public function item(){
    	return $this->hasMany('App\InspectionItem', 'typeId')->where('isActive',1);
    }
}

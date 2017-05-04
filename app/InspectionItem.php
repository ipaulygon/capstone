<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InspectionItem extends Model
{
    protected $table = 'inspection_item';
    public $timestamps = false;
    protected $fillable = [
    	'name',
    	'typeId',
    	'isActive'  	
    ];

    public function type(){
        return $this->belongsTo('App\InspectionType','typeId')->where('isActive',1);
    }
}

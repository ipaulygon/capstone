<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InspectionDetail extends Model
{
    protected $table = 'inspection_detail';
    protected $fillable = [
    	'inspectionId',
        'itemId',
    	'remarks',
        'isActive'
    ];

    public function header(){
        return $this->belongsTo('App\InspectionHeader','inspectionId');
    }
    
    public function item(){
        return $this->belongsTo('App\InspectionItem','itemId');
    }
}

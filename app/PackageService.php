<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageService extends Model
{
    protected $table = 'package_service';
    public $timestamps = false;
    protected $fillable = [
    	'packageId',
    	'serviceId',
    	'isActive'  	
    ];

    public function service(){
        return $this->belongsTo('App\Service','serviceId');
    }

    public function package(){
        return $this->belongsTo('App\Package','packageId');
    }
}

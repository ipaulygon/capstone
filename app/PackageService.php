<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageService extends Model
{
    protected $table = 'package_service';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
    	'packageId',
    	'serviceId',
    	'isActive'  	
    ];

    public function service(){
        return $this->belongsTo('App\Service','serviceId')->where('isActive',1);
    }

    public function package(){
        return $this->belongsTo('App\Package','packageId')->where('isActive',1);
    }
}

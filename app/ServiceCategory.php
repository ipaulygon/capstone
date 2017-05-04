<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $table = 'service_category';
    public $timestamps = false;
    protected $fillable = [
    	'name',
    	'description',
    	'isActive'  	
    ];

    public function service(){
    	return $this->hasMany('App\Service', 'categoryId')->where('isActive',1);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    protected $table = 'rack';
    public $timestamps = false;
    protected $fillable = [
    	'name',
    	'description',
    	'isActive'  	
    ];
}

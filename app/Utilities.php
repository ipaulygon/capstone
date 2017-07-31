<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Utilities extends Model
{
    protected $table = 'utilities';
    public $timestamps = false;
    protected $fillable = [
    	'image',
    	'name',
        'address',
        'category1',
    	'category2',  	
        'type1',
    	'type2',
        'max'  	
    ];
}

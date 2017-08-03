<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    protected $table = 'product_unit';
    public $timestamps = false;
    protected $fillable = [
    	'name',
    	'description',
        'category',
    	'isActive'  	
    ];
}

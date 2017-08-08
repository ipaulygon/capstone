<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReturnHeader extends Model
{
    protected $table = 'return_header';
    public $incrementing = false;
    protected $fillable = [
        'id',
    	'supplierId',
        'dateMake',
        'remarks',
        'isActive'
    ];

    public function detail(){
    	return $this->hasMany('App\ReturnDetail', 'returnId');
    }

    public function order(){
    	return $this->hasMany('App\ReturnOrder', 'returnId');
    }
    
    public function supplier(){
    	return $this->belongsTo('App\Supplier', 'supplierId');
    }
}

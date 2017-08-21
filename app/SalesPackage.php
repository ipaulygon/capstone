<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesPackage extends Model
{
    protected $table = 'sales_package';
    public $timestamps = false;
    protected $fillable = [
    	'salesId',
    	'packageId',
        'quantity',
    	'isActive',
        'isVoid',
    ];

    public function package(){
        return $this->belongsTo('App\Package','packageId');
    }

    public function header(){
        return $this->belongsTo('App\SalesHeader','salesId');
    }
}

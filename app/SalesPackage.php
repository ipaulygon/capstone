<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesPackage extends Model
{
    protected $table = 'sales_package';
    protected $fillable = [
    	'salesId',
    	'packageId',
        'quantity',
    	'isActive',
    ];

    public function package(){
        return $this->belongsTo('App\Package','packageId');
    }

    public function header(){
        return $this->belongsTo('App\SalesHeader','salesId');
    }
}

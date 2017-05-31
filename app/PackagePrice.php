<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackagePrice extends Model
{
    protected $table = 'package_price';
    public $incrementing = false;
    protected $fillable = [
    	'packageId',
    	'price'	
    ];

    public function package(){
        return $this->belongsTo('App\Package','packageId');
    }
}

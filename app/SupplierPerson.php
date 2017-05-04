<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierPerson extends Model
{
    protected $table = 'supplier_person';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
    	'spId',
    	'spName',
    ];
}

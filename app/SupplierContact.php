<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierContact extends Model
{
    protected $table = 'supplier_contact';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
    	'scId',
    	'scNo',
    ];
}

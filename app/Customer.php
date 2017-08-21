<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    public $timestamps = false;
    protected $fillable = [
    	'firstName',
    	'middleName',
        'lastName',
        'street',
        'brgy',
        'city',
        'contact',
        'email',
        'card',
    ];
}

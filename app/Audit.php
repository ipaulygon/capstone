<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $table = 'audit';
    protected $fillable = [
        'userId',
        'name',
        'json'
    ];

    public function user(){
    	return $this->belongsTo('App\User', 'userId');
    }
}

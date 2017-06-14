<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobProduct extends Model
{
    protected $table = 'job_product';
    public $timestamps = false;
    protected $fillable = [
    	'jobId',
    	'productId',
        'quantity',
        'completed',
    	'isActive',
        'isComplete'
    ];

    public function product(){
        return $this->belongsTo('App\Product','productId');
    }

    public function header(){
        return $this->belongsTo('App\JobHeader','jobId');
    }
}

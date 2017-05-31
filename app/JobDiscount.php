<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobDiscount extends Model
{
    protected $table = 'job_discount';
    public $timestamps = false;
    protected $fillable = [
    	'jobId',
    	'discountId',
    	'isActive'  	
    ];

    public function discount(){
        return $this->belongsTo('App\Discount','discountId');
    }

    public function header(){
        return $this->belongsTo('App\JobHeader','jobId');
    }
}

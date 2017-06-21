<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use Redirect;
use Session;
use DB;
use Illuminate\Validation\Rule;
use PDF;
//MODELS
use App\PurchaseHeader;
use App\DeliveryHeader;
use App\EstimateHeader;
use App\JobHeader;
class PdfController extends Controller
{
    public function purchase($id){
        $date = date('Y-m-d H:i:s');
        $purchase = PurchaseHeader::findOrFail($id);
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadview('pdf.purchase',compact('purchase','date'))->setPaper([0,0,612,396]);
        return $pdf->stream('purchase.pdf');
    }
    
    public function delivery($id){
        $date = date('Y-m-d H:i:s');
        $delivery = DeliveryHeader::findOrFail($id);
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadview('pdf.delivery',compact('delivery','date'))->setPaper([0,0,612,396]);
        return $pdf->stream('delivery.pdf');
    }

    public function estimate($id){
        $estId = 'ESTIMATE'.str_pad($id, 5, '0', STR_PAD_LEFT); 
        $date = date('Y-m-d H:i:s');
        $estimate = EstimateHeader::findOrFail($id);
        $total = 0;
        $discounts = 0;
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadview('pdf.estimate',compact('estId','estimate','total','discounts','date'))->setPaper([0,0,612,792]);
        return $pdf->stream('estimate.pdf');
    }

    public function job($id){
        $jobId = 'JOB'.str_pad($id, 5, '0', STR_PAD_LEFT); 
        $date = date('Y-m-d H:i:s');
        $job = JobHeader::findOrFail($id);
        $total = 0;
        $discounts = 0;
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadview('pdf.job',compact('jobId','job','total','discounts','date'))->setPaper([0,0,612,792]);
        return $pdf->stream('job.pdf');
    }
}

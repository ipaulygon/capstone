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
class PdfController extends Controller
{
    public function purchase($id){
        $purchase = PurchaseHeader::findOrFail($id);
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadview('pdf.purchase',compact('purchase'))->setPaper([0,0,612,396]);
        return $pdf->stream('purchase.pdf');
    }
    
    public function delivery($id){
        $delivery = DeliveryHeader::findOrFail($id);
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadview('pdf.delivery',compact('delivery'))->setPaper([0,0,612,396]);
        return $pdf->stream('delivery.pdf');
    }

    public function estimate($id){
        $estimate = EstimateHeader::findOrFail($id);
        $total = 0;
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadview('pdf.estimate',compact('estimate','total'))->setPaper([0,0,612,792]);
        return $pdf->stream('estimate.pdf');
    }
}

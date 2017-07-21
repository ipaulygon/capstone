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
use App\EstimateProduct;
use App\EstimateService;
use App\EstimatePackage;
use App\EstimatePromo;
use App\EstimateDiscount;
use App\EstimateTechnician;
use App\JobHeader;
class PdfController extends Controller
{
    public function purchase($id){
        $date = date('Y-m-d H:i:s');
        $total = 0;
        $purchase = PurchaseHeader::findOrFail($id);
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadview('pdf.purchase',compact('purchase','date','total'))->setPaper([0,0,612,396]);
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
        $job = JobHeader::findOrFail($id);
        try{
            DB::beginTransaction();
            $estimate = EstimateHeader::create([
                'customerId' => $job->customer->id,
                'vehicleId' => $job->vehicle->id,
            ]);
            $products = $job->product;
            $services = $job->service;
            $packages = $job->package;
            $promos = $job->promo;
            $discounts = $job->discount;
            $technicians = $job->technician;
            if(!empty($products)){
                foreach($products as $product){
                    EstimateProduct::create([
                        'estimateId' => $estimate->id,
                        'productId' => $product->productId,
                        'quantity' => $product->quantity,
                    ]);
                }
            }
            if(!empty($services)){
                foreach($services as $service){
                    EstimateService::create([
                        'estimateId' => $estimate->id,
                        'serviceId' => $service->serviceId,
                    ]);
                }
            }
            if(!empty($packages)){
                foreach($packages as $package){
                    EstimatePackage::create([
                        'estimateId' => $estimate->id,
                        'packageId' => $package->packageId,
                        'quantity' => $package->quantity,
                    ]);
                }
            }
            if(!empty($promos)){
                foreach($promos as $promo){
                    EstimatePromo::create([
                        'estimateId' => $estimate->id,
                        'promoId' => $promo->promoId,
                        'quantity' => $promo->quantity,
                    ]);
                }
            }
            if(!empty($discounts)){
                EstimateDiscount::create([
                    'estimateId' => $estimate->id,
                    'discountId' => $discounts->discountId
                ]);
            }
            foreach($technicians as $technician){
                EstimateTechnician::create([
                    'estimateId' => $estimate->id,
                    'technicianId' => $technician->technicianId,
                ]);
            }
            DB::commit();
        }catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            $errMess = $e->getMessage();
            return Redirect::back()->withErrors($errMess);
        }
        $estId = 'ESTIMATE'.str_pad($estimate->id, 5, '0', STR_PAD_LEFT); 
        $date = date('Y-m-d H:i:s');
        $estimate = EstimateHeader::findOrFail($estimate->id);
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

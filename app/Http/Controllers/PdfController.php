<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use Redirect;
use Session;
use DB;
use Illuminate\Validation\Rule;
use PDF;
use Barryvdh\Snappy\Facades\SnappyPdf as PDFSnappy;
//MODELS
use App\PurchaseHeader;
use App\DeliveryHeader;
use App\ReturnHeader;
use App\InspectionHeader;
use App\EstimateHeader;
use App\EstimateProduct;
use App\EstimateService;
use App\EstimatePackage;
use App\EstimatePromo;
use App\EstimateDiscount;
use App\EstimateTechnician;
use App\JobHeader;
use App\JobPayment;
use App\SalesHeader;
use App\WarrantySalesHeader;
use App\WarrantyJobHeader;
use App\Audit;
use Auth;
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
        $attachments = "";
        foreach($delivery->return as $return){
            $attachments .= $return->returnId.'|';
        }
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadview('pdf.delivery',compact('delivery','attachments','date'))->setPaper([0,0,612,396]);
        return $pdf->stream('delivery.pdf');
    }

    public function return($id){
        $date = date('Y-m-d H:i:s');
        $return = ReturnHeader::findOrFail($id);
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadview('pdf.return',compact('return','date'))->setPaper([0,0,612,396]);
        return $pdf->stream('return.pdf');
    }
    
    public function inspect($id){
        $date = date('Y-m-d H:i:s');
        $inspect = InspectionHeader::findOrFail($id);
        // $pdf = PDFSnappy::loadView('pdf.inspect',compact('inspect','date'));
        // return $pdf->inline('github.pdf');
        // PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        // $pdf = PDF::loadview('pdf.inspect',compact('inspect','date'))->setPaper([0,0,612,792]);
        // return $pdf->download('inspect.pdf');
        return View('pdf.inspect',compact('inspect','date'));
    }

    public function estimate(Request $request, $id){
        $picPath = $request->session()->get('signature');
        $date = date('Y-m-d H:i:s');
        $job = JobHeader::findOrFail($id);
        try{
            DB::beginTransaction();
            $estimate = EstimateHeader::create([
                'jobId' => $job->id,
                'customerId' => $job->customer->id,
                'vehicleId' => $job->vehicle->id,
                'rackId' => $job->rackId,
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
            Audit::create([
                'userId' => Auth::id(),
                'name' => "Create Estimate",
                'json' => json_encode($request->all())
            ]);
            DB::commit();
        }catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            $errMess = $e->getMessage();
            return Redirect::back()->withErrors($errMess);
        }
        $estId = 'ESTIMATE'.str_pad($estimate->id, 5, '0', STR_PAD_LEFT); 
        $estimate = EstimateHeader::findOrFail($estimate->id);
        $total = 0;
        $discount = 0;
        $vat = 0;
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadview('pdf.estimate',compact('estId','estimate','total','discount','vat','date','picPath'))->setPaper([0,0,612,792]);
        return $pdf->stream('estimate.pdf');
    }

    public function estimateView(Request $request, $id){
        $picPath = $request->session()->get('signature');
        $date = date('Y-m-d H:i:s');
        $estimate = EstimateHeader::findOrFail($id);
        $estId = 'ESTIMATE'.str_pad($estimate->id, 5, '0', STR_PAD_LEFT); 
        $total = 0;
        $discount = 0;
        $vat = 0;
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadview('pdf.estimate',compact('estId','estimate','total','discount','vat','date','picPath'))->setPaper([0,0,612,792]);
        return $pdf->stream('estimate.pdf');
    }

    public function job(Request $request, $id){
        $picPath = $request->session()->get('signature');
        $jobId = 'JOB'.str_pad($id, 5, '0', STR_PAD_LEFT); 
        $date = date('Y-m-d H:i:s');
        $job = JobHeader::findOrFail($id);
        $total = 0;
        $discount = 0;
        $vat = 0;
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadview('pdf.job',compact('jobId','job','total','discount','vat','date','picPath'))->setPaper([0,0,612,792]);
        return $pdf->stream('job.pdf');
    }
    
    public function jobReceipt($id){
        $payment = JobPayment::findOrFail($id);
        $date = date('Y-m-d H:i:s');
        $paymentId = 'No. '.str_pad($payment->id, 5, '0', STR_PAD_LEFT); 
        $jobId = 'JOB'.str_pad($payment->jobId, 5, '0', STR_PAD_LEFT); 
        $job = JobHeader::findOrFail($payment->jobId);
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadview('pdf.payment',compact('paymentId','payment','jobId','job','date'))->setPaper([0,0,459,306]);
        return $pdf->stream('payment.pdf');
    }

    public function sales($id){
        $salesId = 'INV'.str_pad($id, 5, '0', STR_PAD_LEFT); 
        $date = date('Y-m-d H:i:s');
        $sales = SalesHeader::findOrFail($id);
        $total = 0;
        $discount = 0;
        $vat = 0;
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadview('pdf.sales',compact('salesId','sales','total','discount','vat','date'))->setPaper([0,0,612,396]);
        return $pdf->stream('sales.pdf');
    }

    public function signature(Request $request){
        $pics = str_replace('data:image/png;base64,','',$request->pic);
        $pics = str_replace(' ', '+', $pics);
        $pics = base64_decode($pics);
        $picPath = "pics/".date('YmdHis').'signature.png';
        $signature = file_put_contents($picPath, $pics);
        $request->session()->put('signature',$picPath);
    }

    public function warrantySales($id){
        $warranty = WarrantySalesHeader::findOrFail($id);
        $warrantyId = 'WS'.str_pad($id, 5, '0', STR_PAD_LEFT); 
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadview('pdf.warrantySales',compact('warrantyId','warranty'))->setPaper([0,0,612,396]);
        return $pdf->stream('warrantysales.pdf');
    }
    
    public function warrantyJob($id){
        $warranty = WarrantyJobHeader::findOrFail($id);
        $warrantyId = 'WJ'.str_pad($id, 5, '0', STR_PAD_LEFT); 
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadview('pdf.warrantyJob',compact('warrantyId','warranty'))->setPaper([0,0,612,396]);
        return $pdf->stream('warrantyjob.pdf');
    }
}

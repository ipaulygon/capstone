<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\WarrantySalesHeader;
use App\WarrantySalesProduct;
use App\WarrantySalesPackage;
use App\WarrantySalesPromo;
use App\WarrantyJobHeader;
use App\WarrantyJobProduct;
use App\WarrantyJobService;
use App\WarrantyJobPackageProduct;
use App\WarrantyJobPackageService;
use App\WarrantyJobPromoProduct;
use App\WarrantyJobPromoService;
use App\SalesHeader;
use App\SalesProduct;
use App\SalesPackage;
use App\SalesPromo;
use App\JobHeader;
use App\JobProduct;
use App\JobService;
use App\JobPackage;
use App\JobPromo;
use App\Customer;
use App\Inventory;
use App\Product;
use App\Package;
use App\Promo;
use Validator;
use Redirect;
use Response;
use Session;
use DB;
use App\Audit;
use Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon as Carbon;

class WarrantyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sales = SalesHeader::get();
        $jobs = DB::table('job_header as j')
            ->join('customer as c','c.id','j.customerId')
            ->join('vehicle as v','v.id','j.vehicleId')
            ->join('vehicle_model as vd','vd.id','v.modelId')
            ->join('vehicle_make as vk','vk.id','vd.makeId')
            ->where('j.release','!=',null)
            ->where('j.isVoid','!=',1)
            ->select('j.*','j.id as jobId','c.*','v.*','vd.name as model','v.isManual as transmission','vk.name as make')
            ->get();
        $inventory = DB::table('inventory as i')
            ->join('product as p','p.id','i.productId')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->where('p.isActive',1)
            ->select('i.*','p.name as product','p.isOriginal as isOriginal','pt.name as type','pb.name as brand','pv.name as variance')
            ->get();
        $tab = ($request->session()->has('warranty_tab') ? $request->session()->get('warranty_tab','sales') : $request->session()->put('warranty_tab','sales'));
        $tab = $request->session()->get('warranty_tab');
        return View('warranty.index',compact('sales','jobs','inventory','tab'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('layouts.404');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return View('layouts.404');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        if($request->session()->get('warranty_tab')=='sales'){
            $warranties = WarrantySalesHeader::where('salesId',$id)->get();
            return View('warranty.sales',compact('warranties'));
        }else{
            $warranties = WarrantyJobHeader::where('jobId',$id)->get();
            return View('warranty.job',compact('warranties'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return View('layouts.404');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return View('layouts.404');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return View('layouts.404');
    }

    public function tabSales(Request $request){
        $tab = $request->session()->put('warranty_tab','sales');
        return response()->json('success');
    }
    
    public function tabJobs(Request $request){
        $tab = $request->session()->put('warranty_tab','jobs');
        return response()->json('success');
    }

    public function sales($id){
        $sales = SalesHeader::with('product')
            ->with('package')
            ->with('promo')
            ->findOrFail($id);
        return response()->json(['sales'=>$sales]);
    }
    
    public function salesProduct($id){
        $sales = SalesProduct::findOrFail($id);
        return response()->json($sales);
    }
    
    public function salesPackage($id){
        $sales = SalesPackage::findOrFail($id);
        return response()->json($sales);
    }
    
    public function salesPromo($id){
        $sales = SalesPromo::findOrFail($id);
        return response()->json($sales);
    }

    public function salesCreate(Request $request){
        $rules = [
            'product' => 'required_without_all:packageProduct,promoProduct',
            'productQty.*' => 'sometimes|required|numeric',
            'packageProduct' => 'required_without_all:product,promoProduct',
            'packageProductQty.*' => 'sometimes|required|numeric',
            'promoProduct' => 'required_without_all:product,packageProduct',
            'promoProductQty.*' => 'sometimes|required|numeric',
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
            'regex' => 'The :attribute must not contain special characters.'              
        ];
        $niceNames = [
            'product' => 'Product',
            'productQty.*' => 'Product Quantity',
            'packageProduct' => 'Package',
            'packageProductQty.*' => 'Package(Product) Quantity',
            'promoProduct' => 'Promo',
            'promoProductQty.*' => 'Promo(Product) Quantity',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                $warranty = WarrantySalesHeader::create([
                    'salesId' => $request->salesId,
                ]);
                $products = $request->product;
                $salesProducts = $request->salesProduct;
                $prodQty = $request->productQty;
                $packages = $request->packageProduct;
                $salesPackages = $request->salesPackage;
                $packQty = $request->packageProductQty;
                $promos = $request->promoProduct;
                $salesPromos = $request->salesPromo;
                $promoQty = $request->promoProductQty;
                if(!empty($products)){
                    foreach($products as $key=>$product){
                        if($prodQty[$key]!=0){
                            $inventory = Inventory::where('productId',$product)->first();
                            $inventory->decrement('quantity',$prodQty[$key]);
                            if($inventory->quantity < 0){
                                $product = Product::with(['type','brand','variance'])->findOrFail($product);
                                return response()->json(['message'=>0,'product'=>$product]);
                            }else{
                                WarrantySalesProduct::create([
                                    'warrantyId' => $warranty->id,
                                    'salesProductId' => $salesProducts[$key],
                                    'productId' => $product,
                                    'quantity' => $prodQty[$key]
                                ]);
                            }
                        }
                    }
                }
                if(!empty($packages)){
                    foreach($packages as $key=>$product){
                        if($packQty[$key]!=0){
                            $inventory = Inventory::where('productId',$product)->first();
                            $inventory->decrement('quantity',$packQty[$key]);
                            if($inventory->quantity < 0){
                                $product = Product::with(['type','brand','variance'])->findOrFail($product);
                                return response()->json(['message'=>0,'product'=>$product]);
                            }else{
                                WarrantySalesPackage::create([
                                    'warrantyId' => $warranty->id,
                                    'salesPackageId' => $salesPackages[$key],
                                    'productId' => $product,
                                    'quantity' => $packQty[$key]
                                ]);
                            }
                        }
                    }
                }
                if(!empty($promos)){
                    foreach($promos as $key=>$product){
                        if($promoQty[$key]!=0){
                            $inventory = Inventory::where('productId',$product)->first();
                            $inventory->decrement('quantity',$promoQty[$key]);
                            if($inventory->quantity < 0){
                                $product = Product::with(['type','brand','variance'])->findOrFail($product);
                                return response()->json(['message'=>0,'product'=>$product]);
                            }else{
                                WarrantySalesPromo::create([
                                    'warrantyId' => $warranty->id,
                                    'salesPromoId' => $salesPromos[$key],
                                    'productId' => $product,
                                    'quantity' => $promoQty[$key]
                                ]);
                            }
                        }
                    }
                }
                Audit::create([
                    'userId' => Auth::id(),
                    'name' => "Create Sales Warranty",
                    'json' => json_encode($request->all())
                ]);
                DB::commit();
                $request->session()->flash('warranty', 'Sales warranty successfully added.'); 
                return 'success';
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors($errMess);
            }
        }
    }

    public function job($id){
        $job = JobHeader::with('product')
            ->with('service')
            ->with('package')
            ->with('promo')
            ->findOrFail($id);
        return response()->json(['job'=>$job]);
    }
    
    public function jobProduct($id){
        $job = JobProduct::findOrFail($id);
        return response()->json($job);
    }
    
    public function jobService($id){
        $job = JobService::findOrFail($id);
        return response()->json($job);
    }
    
    public function jobPackage($id){
        $job = JobPackage::findOrFail($id);
        return response()->json($job);
    }
    
    public function jobPromo($id){
        $job = JobPromo::findOrFail($id);
        return response()->json($job);
    }

    public function jobCreate(Request $request){
        $rules = [
            'product' => 'required_without_all:service,packageProduct,packageService,promoProduct,promoService',
            'productQty.*' => 'sometimes|required|numeric',
            'service' => 'required_without_all:product,packageProduct,packageService,promoProduct,promoService',
            'packageProduct' => 'required_without_all:product,service,packageService,promoProduct,promoService',
            'packageProductQty.*' => 'sometimes|required|numeric',
            'packageService' => 'required_without_all:product,service,packageProduct,promoProduct,promoService',
            'promoProduct' => 'required_without_all:product,service,packageProduct,packageService,promoService',
            'promoProductQty.*' => 'sometimes|required|numeric',
            'promoService' => 'required_without_all:product,service,packageProduct,packageService,promoProduct'
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
            'regex' => 'The :attribute must not contain special characters.'              
        ];
        $niceNames = [
            'product' => 'Product',
            'productQty.*' => 'Product Quantity',
            'service' => 'Service',
            'packageProduct' => 'Package',
            'packageProductQty.*' => 'Package(Product) Quantity',
            'packageService' => 'Package(Service)',
            'promoProduct' => 'Promo',
            'promoProductQty.*' => 'Promo(Product) Quantity',
            'promoService' => 'Promo(Service)'
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                $job = JobHeader::findOrFail($request->jobId);
                $job = JobHeader::create([
                    'customerId' => $job->customer->id,
                    'vehicleId' => $job->vehicle->id,
                    'rackId' => $job->rackId,
                    'isFinalize' => 1,
                    'total' => 0,
                    'paid' => 0,
                    'start' => date('Y-m-d H:i:s'),
                    'remarks' => 'On warranty',
                    'isVoid' => 1
                ]);
                $warranty = WarrantyJobHeader::create([
                    'jobId' => $request->jobId,
                    'warrantyJobId' => $job->id
                ]);
                //products
                $products = $request->product;
                $jobProducts = $request->jobProduct;
                $prodQty = $request->productQty;
                //services
                $services = $request->service;
                $jobServices = $request->jobService;
                //pp
                $packageProducts = $request->packageProduct;
                $jobProductPackages = $request->jobProductPackage;
                $packQty = $request->packageProductQty;
                //ps
                $packageServices = $request->packageService;
                $jobServicePackages = $request->jobServicePackage;
                //pp
                $promoProducts = $request->promoProduct;
                $jobProductPromos = $request->jobProductPromo;
                $promoQty = $request->promoProductQty;
                //ps
                $promoServices = $request->promoService;
                $jobServicePromos = $request->jobServicePromo;
                if(!empty($products)){
                    foreach($products as $key=>$product){
                        if($prodQty[$key]!=0){
                            WarrantyJobProduct::create([
                                'warrantyId' => $warranty->id,
                                'jobProductId' => $jobProducts[$key],
                                'productId' => $product,
                                'quantity' => $prodQty[$key]
                            ]);
                            JobProduct::create([
                                'jobId' => $job->id,
                                'productId' => $product,
                                'quantity' => $prodQty[$key],
                                'isActive' => 1,
                                'isVoid' => 1
                            ]);
                        }
                    }
                }
                if(!empty($services)){
                    foreach($services as $key=>$service){
                        WarrantyJobService::create([
                            'warrantyId' => $warranty->id,
                            'jobServiceId' => $jobServices[$key],
                            'serviceId' => $service,
                        ]);
                        JobService::create([
                            'jobId' => $job->id,
                            'serviceId' => $service,
                            'isActive' => 1,
                            'isVoid' => 1
                        ]);
                    }
                }
                if(!empty($packageProducts)){
                    foreach($packageProducts as $key=>$product){
                        WarrantyJobPackageProduct::create([
                            'warrantyId' => $warranty->id,
                            'jobPackageId' => $jobProductPackages[$key],
                            'productId' => $product,
                            'quantity' => $packQty[$key]
                        ]);
                        $checkJob = JobProduct::where('productId',$product)->where('jobId',$job->id)->first();
                        if(count($checkJob)>0){
                            $checkJob->increment('quantity',$packQty[$key]);
                        }else{
                            JobProduct::create([
                                'jobId' => $job->id,
                                'productId' => $product,
                                'quantity' => $packQty[$key],
                                'isActive' => 1,
                                'isVoid' => 1
                            ]);
                        }
                    }
                }
                if(!empty($packageServices)){
                    foreach($packageServices as $key=>$service){
                        WarrantyJobPackageService::create([
                            'warrantyId' => $warranty->id,
                            'jobPackageId' => $jobServicePackages[$key],
                            'serviceId' => $service,
                        ]);
                        JobService::create([
                            'jobId' => $job->id,
                            'serviceId' => $service,
                            'isActive' => 1,
                            'isVoid' => 1
                        ]);
                    }
                }
                if(!empty($promoProducts)){
                    foreach($promoProducts as $key=>$product){
                        WarrantyJobPromoProduct::create([
                            'warrantyId' => $warranty->id,
                            'jobPromoId' => $jobProductPromos[$key],
                            'productId' => $product,
                            'quantity' => $promoQty[$key]
                        ]);
                        $checkJob = JobProduct::where('productId',$product)->where('jobId',$job->id)->first();
                        if(count($checkJob)>0){
                            $checkJob->increment('quantity',$promoQty[$key]);
                        }else{
                            JobProduct::create([
                                'jobId' => $job->id,
                                'productId' => $product,
                                'quantity' => $promoQty[$key],
                                'isActive' => 1,
                                'isVoid' => 1
                            ]);
                        }
                    }
                }
                if(!empty($promoServices)){
                    foreach($promoServices as $key=>$service){
                        WarrantyJobPromoService::create([
                            'warrantyId' => $warranty->id,
                            'jobPromoId' => $jobServicePromos[$key],
                            'serviceId' => $service,
                        ]);
                        JobService::create([
                            'jobId' => $job->id,
                            'serviceId' => $service,
                            'isActive' => 1,
                            'isVoid' => 1
                        ]);
                    }
                }
                Audit::create([
                    'userId' => Auth::id(),
                    'name' => "Create Job Warranty",
                    'json' => json_encode($request->all())
                ]);
                DB::commit();
                $id = 'JOB'.str_pad($job->id, 5, '0', STR_PAD_LEFT);
                $request->session()->flash('warranty', 'Job warranty successfully added. New job order generated: '.$id); 
                return 'success';
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors($errMess);
            }
        }
    }
}

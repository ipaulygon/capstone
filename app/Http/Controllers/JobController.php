<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\JobHeader;
use App\JobProduct;
use App\JobService;
use App\JobPackage;
use App\JobPromo;
use App\JobDiscount;
use App\Product;
use App\Service;
use App\Package;
use App\Promo;
use App\Discount;
use App\Vehicle;
use App\Customer;
use Validator;
use Redirect;
use Response;
use Session;
use DB;
use Illuminate\Validation\Rule;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = DB::table('job_header as j')
            ->join('customer as c','c.id','j.customerId')
            ->join('vehicle as v','v.id','j.vehicleId')
            ->join('vehicle_model as vd','vd.id','v.modelId')
            ->join('vehicle_make as vk','vk.id','vd.makeId')
            ->select('j.*','j.id as jobId','c.*','v.*','vd.name as model','vd.year as year','vd.transmission as transmission','vk.name as make')
            ->get();
        return View('job.index',compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $date = date('Y-m-d');
        $customers = DB::table('customer')
            ->select('customer.*')
            ->get();
        $models = DB::table('vehicle_model as vd')
            ->join('vehicle_make as vk','vd.makeId','vk.id')
            ->select('vd.*','vk.name as make')
            ->get();
        $technicians = DB::table('technician')
            ->where('isActive',1)
            ->select('technician.*')
            ->get();
        $products = DB::table('product as p')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->where('p.isActive',1)
            ->select('p.*','pt.name as type','pb.name as brand','pv.name as variance')
            ->get();
        $services = DB::table('service as s')
            ->join('service_category as c','c.id','s.categoryId')
            ->where('s.isActive',1)
            ->select('s.*','c.name as category')
            ->get();
        $packages = DB::table('package as p')
            ->where('p.isActive',1)
            ->select('p.*')
            ->get();
        $promos = DB::table('promo as p')
            ->where('dateStart','>=',$date)
            ->where('dateEnd','<=',$date)
            ->where('p.isActive',1)
            ->select('p.*')
            ->get();
        $discounts = DB::table('discount as d')
            ->where('d.isActive',1)
            ->where('d.type','Whole')
            ->select('d.*')
            ->get();
        return View('job.create',compact('customers','models','technicians','products','services','packages','promos','discounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'firstName' => 'required|max:100',
            'middleName' => 'max:100',
            'lastName' => 'required|max:100',
            'contact' => 'required',
            'email' => 'nullable|email',
            'address' => 'required|max:140',
            'plate' => 'required',
            'modelId' => 'required',
            'mileage' => 'nullable|between:0,1000000',
            'product.*' => 'sometimes|required',
            'productQty.*' => 'sometimes|required|numeric',
            'service.*' => 'sometimes|required',
            'package.*' => 'sometimes|required',
            'packageQty.*' => 'sometimes|required|numeric',
            'promo.*' => 'sometimes|required',
            'promoQty.*' => 'sometimes|required|numeric',
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'firstName' => 'First Name',
            'middleName' => 'Middle Name',
            'lastName' => 'Last Name',
            'contact' => 'Contact No.',
            'email' => 'Email Address',
            'address' => 'Address',
            'plate' => 'Plate No.',
            'modelId' => 'Vehicle Model',
            'mileage' => 'Mileage',
            'product.*' => 'Product',
            'productQty.*' => 'Product Quantity',
            'service.*' => 'Service',
            'package.*' => 'Package',
            'packageQty.*' => 'Package Quantity',
            'promo.*' => 'Promo Quantity',
            'promoQty.*' => 'Promo Quantity',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                $customer = Customer::updateOrCreate(
                    [
                        'firstName' => trim($request->firstName),
                        'middleName' => trim($request->middleName),
                        'lastName' => trim($request->lastName)
                    ],[
                        'contact' => $request->contact,
                        'email' => $request->email,
                        'address' => trim($request->address),
                    ]
                );
                $vehicle = Vehicle::updateOrCreate(
                    ['plate' => str_replace('_','',trim($request->plate))],
                    [
                        'modelId' => $request->modelId,
                        'mileage' => str_replace(' km','',$request->mileage)
                    ]
                );
                $job = JobHeader::create([
                    'customerId' => $customer->id,
                    'vehicleId' => $vehicle->id,
                    'isFinalize' => 0,
                    'total' => 0,
                    'paid' => 0
                ]);
                $products = $request->product;
                $prodQty = $request->productQty;
                $services = $request->service;
                $packages = $request->package;
                $packQty = $request->packageQty;
                $promos = $request->promo;
                $promoQty = $request->promoQty;
                $discounts = $request->discount;
                if(!empty($products)){
                    foreach($products as $key=>$product){
                        JobProduct::create([
                            'jobId' => $job->id,
                            'productId' => $product,
                            'quantity' => $prodQty[$key],
                            'isActive' => 1
                        ]);
                    }
                }
                if(!empty($services)){
                    foreach($services as $key=>$service){
                        JobService::create([
                            'jobId' => $job->id,
                            'serviceId' => $service,
                            'isActive' => 1
                        ]);
                    }
                }
                if(!empty($packages)){
                    foreach($packages as $key=>$package){
                        JobPackage::create([
                            'jobId' => $job->id,
                            'packageId' => $package,
                            'quantity' => $packQty[$key],
                            'isActive' => 1
                        ]);
                    }
                }
                if(!empty($promos)){
                    foreach($promos as $key=>$promo){
                        JobPromo::create([
                            'jobId' => $job->id,
                            'promoId' => $promo,
                            'quantity' => $promoQty[$key],
                            'isActive' => 1
                        ]);
                    }
                }
                if(!empty($discounts)){
                    foreach($discounts as $key=>$discount){
                        JobDiscount::create([
                            'jobId' => $job->id,
                            'discountId' => $discount,
                            'isActive' => 1
                        ]);
                    }
                }
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors($errMess);
            }
            $request->session()->flash('success', 'Successfully added.');  
            return Redirect::back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return View('layouts.404');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $job = JobHeader::findOrFail($id);
        $date = date('Y-m-d');
        $customers = DB::table('customer')
            ->select('customer.*')
            ->get();
        $models = DB::table('vehicle_model as vd')
            ->join('vehicle_make as vk','vd.makeId','vk.id')
            ->select('vd.*','vk.name as make')
            ->get();
        $technicians = DB::table('technician')
            ->where('isActive',1)
            ->select('technician.*')
            ->get();
        $products = DB::table('product as p')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->where('p.isActive',1)
            ->select('p.*','pt.name as type','pb.name as brand','pv.name as variance')
            ->get();
        $services = DB::table('service as s')
            ->join('service_category as c','c.id','s.categoryId')
            ->where('s.isActive',1)
            ->select('s.*','c.name as category')
            ->get();
        $packages = DB::table('package as p')
            ->where('p.isActive',1)
            ->select('p.*')
            ->get();
        $promos = DB::table('promo as p')
            ->where('dateStart','>=',$date)
            ->where('dateEnd','<=',$date)
            ->where('p.isActive',1)
            ->select('p.*')
            ->get();
        $discounts = DB::table('discount as d')
            ->where('d.isActive',1)
            ->where('d.type','Whole')
            ->select('d.*')
            ->get();
        return View('job.edit',compact('job','customers','models','technicians','products','services','packages','promos','discounts'));
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
        $rules = [
            'firstName' => 'required|max:100',
            'middleName' => 'max:100',
            'lastName' => 'required|max:100',
            'contact' => 'required',
            'email' => 'nullable|email',
            'address' => 'required|max:140',
            'plate' => 'required',
            'modelId' => 'required',
            'mileage' => 'nullable|between:0,1000000',
            'product.*' => 'sometimes|required',
            'productQty.*' => 'sometimes|required|numeric',
            'service.*' => 'sometimes|required',
            'package.*' => 'sometimes|required',
            'packageQty.*' => 'sometimes|required|numeric',
            'promo.*' => 'sometimes|required',
            'promoQty.*' => 'sometimes|required|numeric',
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'firstName' => 'First Name',
            'middleName' => 'Middle Name',
            'lastName' => 'Last Name',
            'contact' => 'Contact No.',
            'email' => 'Email Address',
            'address' => 'Address',
            'plate' => 'Plate No.',
            'modelId' => 'Vehicle Model',
            'mileage' => 'Mileage',
            'product.*' => 'Product',
            'productQty.*' => 'Product Quantity',
            'service.*' => 'Service',
            'package.*' => 'Package',
            'packageQty.*' => 'Package Quantity',
            'promo.*' => 'Promo Quantity',
            'promoQty.*' => 'Promo Quantity',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        else{
            try{
                DB::beginTransaction();
                $customer = Customer::updateOrCreate(
                    [
                        'firstName' => trim($request->firstName),
                        'middleName' => trim($request->middleName),
                        'lastName' => trim($request->lastName)
                    ],[
                        'contact' => $request->contact,
                        'email' => $request->email,
                        'address' => trim($request->address),
                    ]
                );
                $vehicle = Vehicle::updateOrCreate(
                    ['plate' => str_replace('_','',trim($request->plate))],
                    [
                        'modelId' => $request->modelId,
                        'mileage' => str_replace(' km','',$request->mileage)
                    ]
                );
                $job = JobHeader::findOrFail($id);
                $job->update([
                    'customerId' => $customer->id,
                    'vehicleId' => $vehicle->id,
                    'total' => 0,
                    'paid' => 0
                ]);
                $products = $request->product;
                $prodQty = $request->productQty;
                $services = $request->service;
                $packages = $request->package;
                $packQty = $request->packageQty;
                $promos = $request->promo;
                $promoQty = $request->promoQty;
                $discounts = $request->discount;
                JobProduct::where('jobId',$id)->update(['isActive'=>0]);
                JobService::where('jobId',$id)->update(['isActive'=>0]);
                JobPackage::where('jobId',$id)->update(['isActive'=>0]);
                JobPromo::where('jobId',$id)->update(['isActive'=>0]);
                if(!empty($products)){
                    foreach($products as $key=>$product){
                        JobProduct::create([
                            'jobId' => $job->id,
                            'productId' => $product,
                            'quantity' => $prodQty[$key],
                            'isActive' => 1
                        ]);
                    }
                }
                if(!empty($services)){
                    foreach($services as $key=>$service){
                        JobService::create([
                            'jobId' => $job->id,
                            'serviceId' => $service,
                            'isActive' => 1
                        ]);
                    }
                }
                if(!empty($packages)){
                    foreach($packages as $key=>$package){
                        JobPackage::create([
                            'jobId' => $job->id,
                            'packageId' => $package,
                            'quantity' => $packQty[$key],
                            'isActive' => 1
                        ]);
                    }
                }
                if(!empty($promos)){
                    foreach($promos as $key=>$promo){
                        JobPromo::create([
                            'jobId' => $job->id,
                            'promoId' => $promo,
                            'quantity' => $promoQty[$key],
                            'isActive' => 1
                        ]);
                    }
                }
                if(!empty($discounts)){
                    foreach($discounts as $key=>$discount){
                        JobDiscount::create([
                            'jobId' => $job->id,
                            'discountId' => $discount,
                            'isActive' => 1
                        ]);
                    }
                }
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors($errMess);
            }
            $request->session()->flash('success', 'Successfully updated.');  
            return Redirect::back();
        }
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

    public function customer($id)
    {
        $customer = DB::table('customer as c')
            ->where(DB::raw('CONCAT_WS(" ",c.firstName,c.middleName,c.lastName)'),''.$id)
            ->select('c.*')
            ->first();
        return response()->json(['customer'=>$customer]);
    }
    
    public function vehicle($id)
    {
        $vehicle = DB::table('vehicle as v')
            ->where('v.plate',''.$id)
            ->select('v.*')
            ->first();
        if(!empty($vehicle)){
            return response()->json(['vehicle'=>$vehicle]);
        }
    }

    public function product($id){
        $product = Product::with('type')
            ->with('brand')
            ->with('variance')
            ->with('inventory')
            ->with('discount.header.rateRecord')
            ->with('discountRecord.header.rateRecord')
            ->with('priceRecord')
            ->findOrFail($id);
        return response()->json(['product'=>$product]);
    }

    public function service($id){
        $service = Service::with('category')
            ->with('discount.header.rateRecord')
            ->with('discountRecord.header.rateRecord')
            ->with('priceRecord')
            ->findOrFail($id);
        return response()->json(['service'=>$service]);
    }

    public function package($id){
        $package = Package::with('product.product.type')
            ->with('product.product.brand')
            ->with('product.product.variance')
            ->with('service.service.category')
            ->with('priceRecord')
            ->findOrFail($id);
        return response()->json(['package'=>$package]);
    }

    public function promo($id){
        $promo = Promo::with('product.product.type')
            ->with('product.product.brand')
            ->with('product.product.variance')
            ->with('freeProduct.product.type')
            ->with('freeProduct.product.brand')
            ->with('freeProduct.product.variance')
            ->with('service.service.category')
            ->with('freeService.service.category')
            ->with('priceRecord')
            ->findOrFail($id);
        return response()->json(['promo'=>$promo]);
    }

    public function discount($id){
        $discount = Discount::with('rateRecord')->findOrFail($id);
        return response()->json(['discount'=>$discount]);
    }
}

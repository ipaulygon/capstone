<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\EstimateHeader;
use App\EstimateProduct;
use App\EstimateService;
use App\EstimatePackage;
use App\EstimatePromo;
use App\EstimateDiscount;
use App\EstimateTechnician;
use App\Vehicle;
use App\Customer;
use Validator;
use Redirect;
use Response;
use Session;
use DB;
use Illuminate\Validation\Rule;

class EstimateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $estimates = DB::table('estimate_header as e')
            ->join('customer as c','c.id','e.customerId')
            ->join('vehicle as v','v.id','e.vehicleId')
            ->join('vehicle_model as vd','vd.id','v.modelId')
            ->join('vehicle_make as vk','vk.id','vd.makeId')
            ->select('e.*','e.id as estimateId','c.*','v.*','vd.name as model','vd.year as year','vd.transmission as transmission','vk.name as make')
            ->get();
        return View('estimate.index',compact('estimates'));
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
        return View('estimate.create',compact('customers','models','technicians','products','services','packages','promos','discounts'));
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
            'firstName' => 'required|max:45',
            'middleName' => 'max:45',
            'lastName' => 'required|max:45',
            'contact' => 'required',
            'email' => 'nullable|email',
            'street' => 'nullable|max:140',
            'brgy' => 'nullable|max:140',
            'city' => 'required|max:140',
            'plate' => 'required',
            'modelId' => 'required',
            'mileage' => 'nullable|between:0,1000000',
            'technician.*' => 'required',
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
            'street' => 'No. & St./Bldg.',
            'brgy' => 'Brgy./Subd.',
            'city' => 'City/Municipality',
            'plate' => 'Plate No.',
            'modelId' => 'Vehicle Model',
            'mileage' => 'Mileage',
            'technician.*' => 'Technician Assigned',
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
                        'contact' => str_replace('_','',trim($request->contact)),
                        'email' => $request->email,
                        'street' => trim($request->street),
                        'brgy' => trim($request->brgy),
                        'city' => trim($request->city),
                    ]
                );
                $mileage = ($request->mileage==''||$request->mileage==null ? 0 : $request->mileage);
                $vehicle = Vehicle::updateOrCreate(
                    ['plate' => str_replace('_','',trim($request->plate))],
                    [
                        'modelId' => $request->modelId,
                        'mileage' => str_replace(' km','',$mileage)
                    ]
                );
                $estimate = EstimateHeader::create([
                    'customerId' => $customer->id,
                    'vehicleId' => $vehicle->id,
                    'isFinalize' => 0
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
                        EstimateProduct::create([
                            'estimateId' => $estimate->id,
                            'productId' => $product,
                            'quantity' => $prodQty[$key],
                        ]);
                    }
                }
                if(!empty($services)){
                    foreach($services as $key=>$service){
                        EstimateService::create([
                            'estimateId' => $estimate->id,
                            'serviceId' => $service,
                        ]);
                    }
                }
                if(!empty($packages)){
                    foreach($packages as $key=>$package){
                        EstimatePackage::create([
                            'estimateId' => $estimate->id,
                            'packageId' => $package,
                            'quantity' => $packQty[$key],
                        ]);
                    }
                }
                if(!empty($promos)){
                    foreach($promos as $key=>$promo){
                        EstimatePromo::create([
                            'estimateId' => $estimate->id,
                            'promoId' => $promo,
                            'quantity' => $promoQty[$key],
                        ]);
                    }
                }
                if(!empty($discounts)){
                    foreach($discounts as $key=>$discount){
                        EstimateDiscount::create([
                            'estimateId' => $estimate->id,
                            'discountId' => $discount,
                        ]);
                    }
                }
                $technicians = $request->technician;
                foreach($technicians as $technician){
                    EstimateTechnician::create([
                        'estimateId' => $estimate->id,
                        'technicianId' => $technician,
                    ]);
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
        $estimate = EstimateHeader::findOrFail($id);
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
        return View('estimate.edit',compact('estimate','customers','models','technicians','products','services','packages','promos','discounts'));
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
            'firstName' => 'required|max:45',
            'middleName' => 'max:45',
            'lastName' => 'required|max:45',
            'contact' => 'required',
            'email' => 'nullable|email',
            'street' => 'nullable|max:140',
            'brgy' => 'nullable|max:140',
            'city' => 'required|max:140',
            'plate' => 'required',
            'modelId' => 'required',
            'mileage' => 'nullable|between:0,1000000',
            'technician.*' => 'required',
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
            'street' => 'No. & St./Bldg.',
            'brgy' => 'Brgy./Subd.',
            'city' => 'City/Municipality',
            'plate' => 'Plate No.',
            'modelId' => 'Vehicle Model',
            'mileage' => 'Mileage',
            'technician.*' => 'Technician Assigned',
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
                        'contact' => str_replace('_','',trim($request->contact)),
                        'email' => $request->email,
                        'street' => trim($request->street),
                        'brgy' => trim($request->brgy),
                        'city' => trim($request->city),
                    ]
                );
                $mileage = ($request->mileage==''||$request->mileage==null ? 0 : $request->mileage);
                $vehicle = Vehicle::updateOrCreate(
                    ['plate' => str_replace('_','',trim($request->plate))],
                    [
                        'modelId' => $request->modelId,
                        'mileage' => str_replace(' km','',$mileage)
                    ]
                );
                $estimate = EstimateHeader::findOrFail($id);
                $estimate->update([
                    'customerId' => $customer->id,
                    'vehicleId' => $vehicle->id,
                ]);
                $products = $request->product;
                $prodQty = $request->productQty;
                $services = $request->service;
                $packages = $request->package;
                $packQty = $request->packageQty;
                $promos = $request->promo;
                $promoQty = $request->promoQty;
                $discounts = $request->discount;
                EstimateProduct::where('estimateId',$id)->update(['isActive'=>0]);
                EstimateService::where('estimateId',$id)->update(['isActive'=>0]);
                EstimatePackage::where('estimateId',$id)->update(['isActive'=>0]);
                EstimatePromo::where('estimateId',$id)->update(['isActive'=>0]);
                if(!empty($products)){
                    foreach($products as $key=>$product){
                        EstimateProduct::create([
                            'estimateId' => $estimate->id,
                            'productId' => $product,
                            'quantity' => $prodQty[$key],
                        ]);
                    }
                }
                if(!empty($services)){
                    foreach($services as $key=>$service){
                        EstimateService::create([
                            'estimateId' => $estimate->id,
                            'serviceId' => $service,
                        ]);
                    }
                }
                if(!empty($packages)){
                    foreach($packages as $key=>$package){
                        EstimatePackage::create([
                            'estimateId' => $estimate->id,
                            'packageId' => $package,
                            'quantity' => $packQty[$key],
                        ]);
                    }
                }
                if(!empty($promos)){
                    foreach($promos as $key=>$promo){
                        EstimatePromo::create([
                            'estimateId' => $estimate->id,
                            'promoId' => $promo,
                            'quantity' => $promoQty[$key],
                        ]);
                    }
                }
                if(!empty($discounts)){
                    foreach($discounts as $key=>$discount){
                        EstimateDiscount::create([
                            'estimateId' => $estimate->id,
                            'discountId' => $discount,
                        ]);
                    }
                }
                $technicians = $request->technician;
                foreach($technicians as $technician){
                    EstimateTechnician::create([
                        'estimateId' => $estimate->id,
                        'technicianId' => $technician,
                    ]);
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
}

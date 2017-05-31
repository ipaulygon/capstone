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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

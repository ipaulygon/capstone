<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\WarrantySalesHeader;
use App\WarrantySalesProduct;
use App\WarrantySalesPackage;
use App\WarrantySalesPromo;
use App\SalesHeader;
use App\SalesProduct;
use App\SalesPackage;
use App\SalesPromo;
use App\SalesDiscount;
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
            ->select('j.*','j.id as jobId','c.*','v.*','vd.name as model','vd.year as year','v.isManual as transmission','vk.name as make')
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
        if($request->session()->get('warranty_tab')=='sales'){
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
                                    $product = Product::findOrFail($product);
                                    return response()->json(['message'=>0,'product'=>$product->name]);
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
                                    $product = Product::findOrFail($product);
                                    return response()->json(['message'=>0,'product'=>$product->name]);
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
                                    $product = Product::findOrFail($product);
                                    return response()->json(['message'=>0,'product'=>$product->name]);
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
                    DB::commit();
                    return response()->json(['message'=>'Warranty successfully added']);
                }catch(\Illuminate\Database\QueryException $e){
                    DB::rollBack();
                    $errMess = $e->getMessage();
                    return Redirect::back()->withErrors($errMess);
                }
            }
        }else{

        }
    }
}

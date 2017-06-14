<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Promo;
use App\PromoProduct;
use App\PromoService;
use App\PromoPrice;
use App\Product;
use App\Service;
use Validator;
use Redirect;
use Response;
use Session;
use DB;
use Illuminate\Validation\Rule;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promos = Promo::where('isActive',1)->get();
        $deactivate = Promo::where('isActive',0)->get();
        return View('promo.index',compact('promos','deactivate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $date = date('m/d/Y').'-'.date('m/d/Y');
        return View('promo.create',compact('date','products','services'));
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
            'name' => 'required|unique:promo|max:50',
            'price' => 'required|between:0,1000000',
            'stock' => 'between:0,999',
            'date' => 'required',
            'qty.*' => 'sometimes|required|integer|between:0,100',
            'freeQty.*' => 'sometimes|required|integer|between:0,100',
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
        ];
        $niceNames = [
            'name' => 'Promo',
            'price' => 'Price',
            'qty.*' => 'Product Quantity',
            'freeQty.*' => 'Free Product Quantity',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                $dates = explode('-',$request->date); // two dates MM/DD/YYYY-MM/DD/YYYY
                $startDate = explode('/',$dates[0]); // MM[0] DD[1] YYYY[2] 
                $finalStartDate = "$startDate[2]-$startDate[0]-$startDate[1]";
                $endDate = explode('/',$dates[1]); // MM[0] DD[1] YYYY[2] 
                $finalEndDate = "$endDate[2]-$endDate[0]-$endDate[1]";
                $stock = (trim($request->stock) == '' ? null : trim($request->stock));
                $promo = Promo::create([
                    'name' => trim($request->name),
                    'price' => trim(str_replace(',','',$request->price)),
                    'dateStart' => $finalStartDate,
                    'dateEnd' => $finalEndDate,
                    'stock' => $stock,
                ]);
                $products = $request->product;
                $qty = $request->qty;
                $services = $request->service;
                $fproducts = $request->freeProduct;
                $fqty = $request->freeQty;
                $fservices = $request->freeService;
                if(!empty($products)){
                    foreach ($products as $key=>$product) {
                        PromoProduct::create([
                            'promoId' => $promo->id,
                            'productId' => $product,
                            'quantity' => $qty[$key],
                            'isFree' => 0,
                        ]);
                    }
                }
                if(!empty($fproducts)){
                    foreach ($fproducts as $key=>$product) {
                        PromoProduct::create([
                            'promoId' => $promo->id,
                            'productId' => $product,
                            'quantity' => $fqty[$key],
                            'isFree' => 1,
                        ]);
                    }
                }
                if(!empty($services)){
                    foreach ($services as $service) {
                        PromoService::create([
                            'promoId' => $promo->id,
                            'serviceId' => $service,
                            'isFree' => 0,
                        ]);
                    }
                }
                if(!empty($fservices)){
                    foreach ($fservices as $service) {
                        PromoService::create([
                            'promoId' => $promo->id,
                            'serviceId' => $service,
                            'isFree' => 1,
                        ]);
                    }
                }
                PromoPrice::create([
                    'promoId' => $promo->id,
                    'price' => trim(str_replace(',','',$request->price))
                ]);
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
        $promo = Promo::findOrFail($id);
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
        $startDate = explode('-',$promo->dateStart);
        $finalStartDate = "$startDate[1]/$startDate[2]/$startDate[0]";
        $endDate = explode('-',$promo->dateEnd);
        $finalEndDate = "$endDate[1]/$endDate[2]/$endDate[0]";
        $date = "$finalStartDate-$finalEndDate";
        return View('promo.edit',compact('promo','date','products','services'));
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
            'name' => ['required','max:50',Rule::unique('promo')->ignore($id)],
            'price' => 'required|between:0,1000000',
            'stock' => 'between:0,999',
            'date' => 'required',
            'qty.*' => 'sometimes|required|integer|between:0,100',
            'freeQty.*' => 'sometimes|required|integer|between:0,100',
        ];
        $messages = [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
        ];
        $niceNames = [
            'name' => 'Promo',
            'price' => 'Price',
            'qty.*' => 'Product Quantity',
            'freeQty.*' => 'Free Product Quantity',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        else{
            try{
                DB::beginTransaction();
                $dates = explode('-',$request->date); // two dates MM/DD/YYYY-MM/DD/YYYY
                $startDate = explode('/',$dates[0]); // MM[0] DD[1] YYYY[2] 
                $finalStartDate = "$startDate[2]-$startDate[0]-$startDate[1]";
                $endDate = explode('/',$dates[1]); // MM[0] DD[1] YYYY[2] 
                $finalEndDate = "$endDate[2]-$endDate[0]-$endDate[1]";
                $stock = (trim($request->stock) == '' ? 0 : trim($request->stock));
                $promo = Promo::findOrFail($id);
                $promo->update([
                    'name' => trim($request->name),
                    'price' => trim(str_replace(',','',$request->price)),
                    'dateStart' => $finalStartDate,
                    'dateEnd' => $finalEndDate,
                    'stock' => $stock,
                ]);
                $products = $request->product;
                $qty = $request->qty;
                $services = $request->service;
                $fproducts = $request->freeProduct;
                $fqty = $request->freeQty;
                $fservices = $request->freeService;
                PromoProduct::where('promoId',$id)->update(['isActive'=>0]);
                PromoService::where('promoId',$id)->update(['isActive'=>0]); 
                if(!empty($products)){
                    foreach ($products as $key=>$product) {
                        PromoProduct::updateOrCreate([
                            'promoId' => $id,
                            'productId' => $product,
                            'isFree' => 0,
                        ],[
                            'quantity' => $qty[$key],
                            'isActive' => 1
                        ]);
                    }
                }
                if(!empty($fproducts)){
                    foreach ($fproducts as $key=>$product) {
                        PromoProduct::updateOrCreate([
                            'promoId' => $id,
                            'productId' => $product,
                            'isFree' => 1,
                        ],[
                            'quantity' => $fqty[$key],
                            'isActive' => 1
                        ]);
                    }
                }
                if(!empty($services)){
                    foreach ($services as $service) {
                        PromoService::updateOrCreate([
                            'promoId' => $id,
                            'serviceId' => $service,
                            'isFree' => 0,
                        ],[
                            'isActive' => 1
                        ]);
                    }
                }
                if(!empty($fservices)){
                    foreach ($fservices as $service) {
                        PromoService::updateOrCreate([
                            'promoId' => $id,
                            'serviceId' => $service,
                            'isFree' => 1,
                        ],[
                            'isActive' => 1
                        ]);
                    }
                }
                PromoPrice::create([
                    'promoId' => $id,
                    'price' => trim(str_replace(',','',$request->price))
                ]);
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
    public function destroy(Request $request,$id)
    {
        $item = Promo::findOrFail($id);
        $item->update([
            'isActive' => 0
        ]);
        $request->session()->flash('success', 'Successfully deactivated.');  
        return Redirect::back();
    }
    
    public function reactivate(Request $request,$id)
    {
        $item = Promo::findOrFail($id);
        $item->update([
            'isActive' => 1
        ]);
        $request->session()->flash('success', 'Successfully reactivated.');  
        return Redirect::back();
    }

    public function product($id){
        $product = Product::with('type')->with('brand')->with('variance')->findOrFail($id);
        return response()->json(['product'=>$product]);
    }

    public function service($id){
        $service = Service::with('category')->findOrFail($id);
        return response()->json(['service'=>$service]);
    }
}

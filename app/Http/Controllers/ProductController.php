<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Product;
use App\ProductPrice;
use App\Inventory;
use App\ProductType;
use App\TypeBrand;
use App\TypeVariance;
use App\ProductVehicle;
use Validator;
use Redirect;
use Response;
use Session;
use DB;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = DB::table('product as p')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->where('p.isActive',1)
            ->select('p.*','pt.name as type','pb.name as brand','pv.name as variance')
            ->get();
        $deactivate = DB::table('product as p')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->where('p.isActive',0)
            ->select('p.*','pt.name as type','pb.name as brand','pv.name as variance')
            ->get();
        return View('product.index',compact('products','deactivate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = DB::table('product')
            ->select('*')
            ->get();
        $types [] = [];
        $brands [] = [];
        $variances [] = [];
        $types = ProductType::where('isActive',1)->orderBy('name')->get();
        if($types->isNotEmpty()){
            $brands = TypeBrand::where('typeId',$types->first()->id)->get();
            $variances = TypeVariance::where('typeId',$types->first()->id)->get();
        }
        $autos = DB::table('vehicle_model as vd')
            ->join('vehicle_make as vk','vd.makeId','vk.id')
            ->select('vd.*','vk.name as make')
            ->where('hasAuto',1)
            ->where('vd.isActive',1)
            ->get();
        $manuals = DB::table('vehicle_model as vd')
            ->join('vehicle_make as vk','vd.makeId','vk.id')
            ->select('vd.*','vk.name as make')
            ->where('hasManual',1)
            ->where('vd.isActive',1)
            ->get();
        return View('product.create',compact('products','types','brands','variances','autos','manuals'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $part = ProductType::findOrFail($request->typeId)->first()->category;
        if($part=='category2'){
            $rules = [
                'name' => ['required','max:50',Rule::unique('product')->where('typeId',$request->typeId)->where('brandId',$request->brandId)->where('varianceId',$request->varianceId)],
                'description' => 'max:50',
                'price' => 'required|between:0,500000',
                'reorder' => 'required|integer|between:0,100',
                'typeId' => 'required',
                'brandId' => 'required',
                'varianceId' => 'required',
            ];
        }else{
            $rules = [
                'name' => ['required','max:50',Rule::unique('product')->where('typeId',$request->typeId)->where('brandId',$request->brandId)->where('varianceId',$request->varianceId)->where('isOriginal',$request->isOriginal)],
                'description' => 'max:50',
                'price' => 'required|between:0,500000',
                'reorder' => 'required|integer|between:0,100',
                'typeId' => 'required',
                'brandId' => 'required',
                'varianceId' => 'required',
                'isOriginal' => 'required'
            ];
        }
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
        ];
        $niceNames = [
            'name' => 'Product',
            'description' => 'Description',
            'price' => 'Price',
            'reorder' => 'Reorder Level',
            'typeId' => 'Product Type',
            'brandId' => 'Product Brand',
            'varianceId' => 'Product Variance',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                $product = Product::create([
                    'name' => trim($request->name),
                    'description' => trim($request->description),
                    'price' => trim(str_replace(',','',$request->price)),
                    'reorder' => trim($request->reorder),
                    'typeId' => $request->typeId,
                    'brandId' => $request->brandId,
                    'varianceId' => $request->varianceId,
                    'isOriginal' => $request->isOriginal,
                ]);
                $vehicles = $request->vehicle;
                if(!empty($vehicles)){
                    foreach($vehicles as $vehicle){
                        $v = explode(',',$vehicle);
                        ProductVehicle::create([
                            'productId' => $product->id,
                            'modelId' => $v[0],
                            'isManual' => $v[1]
                        ]);
                    }
                }
                ProductPrice::create([
                    'productId' => $product->id,
                    'price' => trim(str_replace(',','',$request->price))
                ]);
                Inventory::create([
                    'productId' => $product->id,
                    'quantity' => 0
                ]);
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors("Oops! This has not been developed yet");
            }
            $request->session()->flash('success', 'Successfully added.');
            return Redirect('product');
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
        $product = Product::findOrFail($id);
        $products = DB::table('product')
            ->select('*')
            ->get();
        $types = ProductType::where('isActive',1)->orderBy('name')->get();
        $brands = TypeBrand::where('typeId',$product->typeId)->get();
        $variances = TypeVariance::where('typeId',$product->typeId)->get();
        $autos = DB::table('vehicle_model as vd')
        ->join('vehicle_make as vk','vd.makeId','vk.id')
        ->select('vd.*','vk.name as make')
        ->where('hasAuto',1)
        ->where('vd.isActive',1)
        ->get();
        $manuals = DB::table('vehicle_model as vd')
            ->join('vehicle_make as vk','vd.makeId','vk.id')
            ->select('vd.*','vk.name as make')
            ->where('hasManual',1)
            ->where('vd.isActive',1)
            ->get();
        return View('product.edit',compact('product','products','types','brands','variances','autos','manuals'));
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
        $part = ProductType::findOrFail($request->typeId)->first()->category;
        if($part=='category2'){
            $rules = [
                'name' => ['required','max:50',Rule::unique('product')->where('typeId',$request->typeId)->where('brandId',$request->brandId)->where('varianceId',$request->varianceId)->ignore($id)],
                'description' => 'max:50',
                'price' => 'required|between:0,500000',
                'reorder' => 'required|integer|between:0,100',
                'typeId' => 'required',
                'brandId' => 'required',
                'varianceId' => 'required',
            ];
        }else{
            $rules = [
                'name' => ['required','max:50',Rule::unique('product')->where('typeId',$request->typeId)->where('brandId',$request->brandId)->where('varianceId',$request->varianceId)->where('isOriginal',$request->isOriginal)->ignore($id)],
                'description' => 'max:50',
                'price' => 'required|between:0,500000',
                'reorder' => 'required|integer|between:0,100',
                'typeId' => 'required',
                'brandId' => 'required',
                'varianceId' => 'required',
                'isOriginal' => 'required'
            ];
        }
        $messages = [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
        ];
        $niceNames = [
            'name' => 'Product',
            'description' => 'Description',
            'price' => 'Price',
            'reorder' => 'Reorder Level',
            'typeId' => 'Product Type',
            'brandId' => 'Product Brand',
            'varianceId' => 'Product Variance',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        else{
            try{
                DB::beginTransaction();
                $product = Product::findOrFail($id);
                $product->update([
                    'name' => trim($request->name),
                    'description' => trim($request->description),
                    'price' => trim(str_replace(',','',$request->price)),
                    'reorder' => trim($request->reorder),
                    'typeId' => $request->typeId,
                    'brandId' => $request->brandId,
                    'varianceId' => $request->varianceId,
                    'isOriginal' => $request->isOriginal,
                ]);
                ProductVehicle::where('productId',$id)->update(['isActive'=>0]);
                $vehicles = $request->vehicle;
                if(!empty($vehicles)){
                    foreach($vehicles as $vehicle){
                        $v = explode(',',$vehicle);
                        ProductVehicle::updateOrCreate(
                            ['productId' => $product->id,
                            'modelId' => $v[0],
                            'isManual' => $v[1]],
                            ['isActive' => 1]
                        );
                    }
                }
                ProductPrice::create([
                    'productId' => $id,
                    'price' => str_replace(',','',$request->price)
                ]);
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors("Oops! This has not been developed yet");
            }
            $request->session()->flash('success', 'Successfully updated.');
            return Redirect('product');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try{
            DB::beginTransaction();
            $checkPackage = DB::table('package_product')
                ->where('productId',$id)
                ->where('isActive',1)
                ->get();
            $checkPromo = DB::table('promo_product')
                ->where('productId',$id)
                ->where('isActive',1)
                ->get();
            $checkPurchase = DB::table('purchase_detail')
                ->where('productId',$id)
                ->where('quantity','!=','delivered')
                ->get();
            $checkInventory = DB::table('inventory')
                ->where('productId',$id)
                ->where('quantity','>',0)
                ->get();
            if(count($checkPackage) > 0 || count($checkPromo) > 0 || count($checkPurchase) > 0 || count($checkInventory) > 0){
                $request->session()->flash('error', 'It seems that the record is still being used in other items. Deactivation failed.');
            }else{
                $product = Product::findOrFail($id);
                $product->update([
                    'isActive' => 0
                ]);
                $request->session()->flash('success', 'Successfully deactivated.');  
            }
            DB::commit();
        }catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            $errMess = $e->getMessage();
            return Redirect::back()->withErrors("Oops! This has not been developed yet");
        }
        return Redirect('product');
    }
    
    public function reactivate(Request $request, $id)
    {
        try{
            DB::beginTransaction();
            $product = Product::findOrFail($id);
            $product->update([
                'isActive' => 1
            ]);
            DB::commit();
        }catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            $errMess = $e->getMessage();
            return Redirect::back()->withErrors("Oops! This has not been developed yet");
        }
        $request->session()->flash('success', 'Successfully reactivated.');  
        return Redirect('product');
    }

    public function type($id){
        $brands [] = [];
        $variances [] = [];
        $type = ProductType::findOrfail($id);
        $brands = TypeBrand::with('brand')->where('typeId',$id)->get();
        $variances = TypeVariance::with('variance')->where('typeId',$id)->get();
        return response()->json(['type'=>$type,'brands'=>$brands,'variances'=>$variances]);
    }
}

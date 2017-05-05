<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Product;
use App\ProductPrice;
use App\Inventory;
use App\ProductType;
use App\TypeBrand;
use App\TypeVariance;
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
        $products = Product::where('isActive',1)->get();
        return View('product.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types [] = [];
        $brands [] = [];
        $variances [] = [];
        $types = ProductType::where('isActive',1)->orderBy('name')->get();
        if($types->isNotEmpty()){
            $brands = TypeBrand::where('typeId',$types->first()->id)->get();
            $variances = TypeVariance::where('typeId',$types->first()->id)->get();
        }
        return View('product.create',compact('types','brands','variances'));
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
            'name' => 'required|unique:product|max:50',
            'description' => 'max:50',
            'price' => 'required|numeric|between:0,10000',
            'reorder' => 'required|numeric|between:0,100',
            'typeId' => 'required',
            'brandId' => 'required',
            'varianceId' => 'required',
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
            'numeric' => 'The :attribute field must be a valid number.',
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
                Product::create([
                    'name' => trim($request->name),
                    'description' => trim($request->description),
                    'price' => trim($request->price),
                    'reorder' => trim($request->reorder),
                    'typeId' => $request->typeId,
                    'brandId' => $request->brandId,
                    'varianceId' => $request->varianceId,
                    'isActive' => 1
                ]);
                $product = Product::all()->last();
                ProductPrice::create([
                    'productId' => $product->id,
                    'price' => trim($request->price)
                ]);
                Inventory::create([
                    'productId' => $product->id,
                    'quantity' => 0
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
        $product = Product::findOrFail($id);
        $types = ProductType::where('isActive',1)->orderBy('name')->get();
        $brands = TypeBrand::where('typeId',$product->typeId)->get();
        $variances = TypeVariance::where('typeId',$product->typeId)->get();
        return View('product.edit',compact('product','types','brands','variances'));
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
            'name' => ['required','max:50',Rule::unique('product')->ignore($id)],
            'description' => 'max:50',
            'price' => 'required|numeric|between:0,10000',
            'reorder' => 'required|numeric|between:0,100',
            'typeId' => 'required',
            'brandId' => 'required',
            'varianceId' => 'required',
        ];
        $messages = [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
            'numeric' => 'The :attribute field must be a valid number.',
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
                $product = Product::findOrFail($id);
                $product->update([
                    'name' => trim($request->name),
                    'description' => trim($request->description),
                    'price' => trim($request->price),
                    'reorder' => trim($request->reorder),
                    'typeId' => $request->typeId,
                    'brandId' => $request->brandId,
                    'varianceId' => $request->varianceId,
                    'isActive' => 1
                ]);
                ProductPrice::create([
                    'productId' => $id,
                    'price' => $request->price
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
    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update([
            'isActive' => 0
        ]);
        $request->session()->flash('success', 'Successfully deactivated.');  
        return Redirect::back();
    }

    public function type($id){
        $brands [] = [];
        $variances [] = [];
        $brands = TypeBrand::with('brand')->where('typeId',$id)->get();
        $variances = TypeVariance::with('variance')->where('typeId',$id)->get();
        return response()->json(['brands'=>$brands,'variances'=>$variances]);
    }
}

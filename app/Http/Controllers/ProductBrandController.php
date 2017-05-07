<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\ProductType;
use App\ProductBrand;
use App\TypeBrand;
use Validator;
use Redirect;
use Session;
use DB;
use Illuminate\Validation\Rule;

class ProductBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$brands = ProductBrand::where('isActive',1)->get();
        $brands = DB::table('product_brand')
        ->join('type_brand','type_brand.brandId','product_brand.id')
        ->join('product_type','type_brand.typeId','product_type.id')
        ->where('product_brand.isActive',1)
        ->distinct()
        ->select('product_brand.*','product_type.name as types')->get();
        return View('brand.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = ProductType::where('isActive',1)->get();
        return View('brand.create',compact('types'));
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
            'name' => 'required|unique:product_brand|max:50',
            'type' => 'required',
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'name' => 'Product Brand',
            'type' => 'Product Type(s)',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                ProductBrand::create([
                    'name' => trim($request->name),
                    'isActive' => 1
                ]);
                $brand = ProductBrand::all()->last();
                $types = $request->type;
                foreach ($types as $type) {
                    TypeBrand::updateOrCreate(
                        ['typeId' => $type,'brandId' => $brand->id],
                        [
                            'typeId' => $type,
                            'brandId' => $brand->id
                        ]
                    );
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
        $brand = ProductBrand::findOrFail($id);
        $types = ProductType::where('isActive',1)->get();
        return View('brand.edit',compact('brand','types'));
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
            'name' => ['required','max:50',Rule::unique('product_brand')->ignore($id)],
            'type' => 'required',
        ];
        $messages = [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'name' => 'Product Brand',
            'type' => 'Product Type(s)',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                $brand = ProductBrand::findOrFail($id);
                $brand->update([
                    'name' => trim($request->name),
                ]);
                TypeBrand::where('brandId',$id)->delete();
                $types = $request->type;
                foreach ($types as $type) {
                    TypeBrand::updateOrCreate(
                        ['typeId' => $type,'brandId' => $id],
                        [
                            'typeId' => $type,
                            'brandId' => $id
                        ]
                    );
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
    public function destroy(Request $request, $id)
    {
        $brand = ProductBrand::findOrFail($id);
        $brand->update([
            'isActive' => 0
        ]);
        $request->session()->flash('success', 'Successfully deactivated.');  
        return Redirect::back();
    }
}

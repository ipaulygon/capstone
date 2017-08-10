<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\ProductVariance;
use App\ProductType;
use App\TypeVariance;
use App\ProductUnit;
use App\Product;
use Validator;
use Redirect;
use Session;
use DB;
use Illuminate\Validation\Rule;

class ProductVarianceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $variances = ProductVariance::where('isActive',1)->get();
        $deactivate = ProductVariance::where('isActive',0)->get();
        return View('variance.index', compact('variances','deactivate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $units = ProductUnit::where('category',1)->where('isActive',1)->get();
        $types = ProductType::where('isActive',1)->get();
        return View('variance.create',compact('units','types'));
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
            'name' => 'required|unique:product_variance|max:50',
            'dimension.*' => 'required|integer',
            'unit.*' => 'required',
            'type' => 'required'
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'name' => 'Product Variance',
            'dimension.*' => 'Dimension',
            'unit.*' => 'Unit',
            'type' => 'Product Type(s)'
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            try{
                DB::beginTransaction();
                $sizes = implode(',', $request->dimension);
                $units = implode(',', $request->unit);
                $variance = ProductVariance::create([
                    'name' => trim($request->name),
                    'size' => $sizes,
                    'units' => $units,
                ]);
                $types = $request->type;
                foreach ($types as $type) {
                    TypeVariance::updateOrCreate(
                        ['typeId' => $type,'varianceId' => $variance->id],
                        [
                            'typeId' => $type,
                            'varianceId' => $variance->id
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
            return Redirect('variance');
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
        $variance = ProductVariance::findOrFail($id);
        $activeSize = explode(',', $variance->size);
        $activeUnit = explode(',', $variance->units);
        $unitTest = ProductUnit::find($activeUnit[0]);
        $category = $unitTest->category;
        $units = ProductUnit::where('category',$category)->where('isActive',1)->get();
        $types = ProductType::where('isActive',1)->get();
        return View('variance.edit',compact('variance','units','types','activeSize','activeUnit','category'));
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
            'name' => ['required','max:50',Rule::unique('product_variance')->ignore($id)],
            'dimension.*' => 'required|integer',
            'unit.*' => 'required',
            'type' => 'required'
        ];
        $messages = [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'name' => 'Product Variance',
            'dimension.*' => 'Dimension',
            'unit.*' => 'Unit',
            'type' => 'Product Type(s)'
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }else{
            try{
                DB::beginTransaction();
                $sizes = implode(',', $request->dimension);
                $units = implode(',', $request->unit);
                $variance = ProductVariance::findOrFail($id);
                $variance->update([
                    'name' => trim($request->name),
                    'size' => $sizes,
                    'units' => $units,
                ]);
                TypeVariance::where('varianceId',$id)->delete();
                $types = $request->type;
                foreach ($types as $type) {
                    TypeVariance::updateOrCreate(
                        ['typeId' => $type,'varianceId' => $id],
                        [
                            'typeId' => $type,
                            'varianceId' => $id
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
            return Redirect('variance');
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
        $checkProduct = DB::table('product')
            ->where('varianceId',$id)
            ->get();
        if(count($checkProduct) > 0){
            $request->session()->flash('error', 'It seems that the record is still being used in other items. Deactivation failed.');
        }else{
            $variance = Productvariance::findOrFail($id);
            $variance->update([
                'isActive' => 0
            ]);
            TypeVariance::where('varianceId',$id)->delete();
            $request->session()->flash('success', 'Successfully deactivated.');  
        }
        return Redirect('variance');
    }

    public function reactivate(Request $request, $id)
    {
        $variance = Productvariance::findOrFail($id);
        $variance->update([
            'isActive' => 1
        ]);
        $request->session()->flash('success', 'Successfully deactivated.'); 
        return Redirect('variance');
    }

    public function category($id)
    {
        $units = [];
        $units = ProductUnit::where('category',$id)->get();
        return response()->json(['units'=>$units]);
    }
}

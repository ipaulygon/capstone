<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\ServiceCategory;
use App\Service;
use Validator;
use Redirect;
use Session;
use DB;
use Illuminate\Validation\Rule;

class ServiceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = ServiceCategory::where('isActive',1)->get();
        $deactivate = ServiceCategory::where('isActive',0)->get();
        return View('category.index',compact('categories','deactivate'));
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
        $rules = [
            'name' => 'required|unique:service_category|max:50',
            'description' => 'max:50',
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'name' => 'Service Category',
            'description' => 'Description',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                ServiceCategory::create([
                    'name' => trim($request->name),
                    'description' => trim($request->description),
                ]);
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors($errMess);
            }
            $request->session()->flash('success', 'Successfully added.');  
            return Redirect('category');
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
        $category = ServiceCategory::findOrFail($id);
        return response()->json(['category'=>$category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = [
            'name' => ['required','max:50',Rule::unique('service_category')->ignore($request->id)],
            'description' => 'max:50',
        ];
        $messages = [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'name' => 'Service Category',
            'description' => 'Description',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                $category = ServiceCategory::findOrFail($request->id);
                $category->update([
                    'name' => trim($request->name),
                    'description' => trim($request->description),
                ]);
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors($errMess);
            }
            $request->session()->flash('success', 'Successfully updated.');  
            return Redirect('category');
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
        $checkService = DB::table('service')
            ->where('categoryId',$id)
            ->where('isActive',1)
            ->get();
        if(count($checkService) > 0){
            $request->session()->flash('error', 'It seems that the record is still being used in other items. Deactivation failed.');
        }else{
            $category = ServiceCategory::findOrFail($id);
            $category->update([
                'isActive' => 0
            ]);
            $request->session()->flash('success', 'Successfully deactivated.');  
        }
        return Redirect('category');
    }
    
    public function reactivate(Request $request, $id)
    {
        $category = ServiceCategory::findOrFail($id);
        $category->update([
            'isActive' => 1
        ]);
        $request->session()->flash('success', 'Successfully reactivated.');  
        return Redirect('category');
    }
}

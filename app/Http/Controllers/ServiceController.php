<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Service;
use App\ServicePrice;
use App\ServiceCategory;
use Validator;
use Redirect;
use Session;
use DB;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::where('isActive',1)->get();
        return View('service.index',compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories [] = [];
        $categories = ServiceCategory::where('isActive',1)->orderBy('name')->get();
        return View('service.create',compact('categories'));
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
            'name' => 'required|unique:service|max:50',
            'categoryId' => 'required',
            'size' => 'required',
            'price' => 'required|numeric|between:0,10000'
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
            'numeric' => 'The :attribute field must be a valid number.',
        ];
        $niceNames = [
            'name' => 'Service',
            'categoryId' => 'Service Category',
            'size' => 'Size',
            'price' => 'Price'
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                Service::create([
                    'name' => trim($request->name),
                    'categoryId' => $request->categoryId,
                    'size' => $request->size,
                    'price' => trim($request->price),
                    'isActive' => 1
                ]);
                $service = Service::all()->last();
                ServicePrice::create([
                    'serviceId' => $service->id,
                    'price' => trim($request->price)
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
        $service = Service::findOrFail($id);
        $categories = ServiceCategory::where('isActive',1)->orderBy('name')->get();
        return View('service.edit',compact('service','categories'));
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
            'name' => ['required','max:50',Rule::unique('service')->ignore($id)],
            'categoryId' => 'required',
            'size' => 'required',
            'price' => 'required|numeric|between:0,10000'
        ];
        $messages = [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
            'numeric' => 'The :attribute field must be a valid number.',
            'between' => 'The :attribute must be :between only.',
        ];
        $niceNames = [
            'name' => 'Service',
            'categoryId' => 'Service Category',
            'size' => 'Size',
            'price' => 'Price'
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                $service = Service::findOrFail($id);
                $service->update([
                    'name' => trim($request->name),
                    'categoryId' => $request->categoryId,
                    'size' => $request->size,
                    'price' => trim($request->price),
                ]);
                ServicePrice::create([
                    'serviceId' => $service->id,
                    'price' => trim($request->price)
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
        $service = Service::findOrFail($id);
        $service->update([
            'isActive' => 0
        ]);
        $request->session()->flash('success', 'Successfully deactivated.');  
        return Redirect::back();
    }
}

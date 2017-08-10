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
        $services = DB::table('service as s')
            ->join('service_category as c','c.id','s.categoryId')
            ->where('s.isActive',1)
            ->select('s.*','c.name as category')
            ->get();
        $deactivate = DB::table('service as s')
            ->join('service_category as c','c.id','s.categoryId')
            ->where('s.isActive',0)
            ->select('s.*','c.name as category')
            ->get();
        return View('service.index',compact('services','deactivate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
            'name' => ['required','max:50',Rule::unique('service')->where('size',$request->size)],
            'categoryId' => 'required',
            'size' => 'required',
            'price' => 'required|between:0,500000'
        ];
        $messages = [
            'name.unique' => 'Service is already in records.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
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
                $service = Service::create([
                    'name' => trim($request->name),
                    'categoryId' => $request->categoryId,
                    'size' => $request->size,
                    'price' => trim(str_replace(',','',$request->price)),
                ]);
                ServicePrice::create([
                    'serviceId' => $service->id,
                    'price' => trim(str_replace(',','',$request->price))
                ]);
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors($errMess);
            }
            $request->session()->flash('success', 'Successfully added.');  
            return Redirect('service');
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
            'name' => ['required','max:50',Rule::unique('service')->where('size',$request->size)->ignore($id)],
            'categoryId' => 'required',
            'size' => 'required',
            'price' => 'required|between:0,500000'
        ];
        $messages = [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
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
                    'price' => trim(str_replace(',','',$request->price)),
                ]);
                ServicePrice::create([
                    'serviceId' => $service->id,
                    'price' => trim(str_replace(',','',$request->price))
                ]);
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors($errMess);
            }
            $request->session()->flash('success', 'Successfully updated.');  
            return Redirect('service');
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
        $checkPackage = DB::table('package_service')
            ->where('serviceId',$id)
            ->where('isActive',1)
            ->get();
        $checkPromo = DB::table('promo_service')
            ->where('serviceId',$id)
            ->where('isActive',1)
            ->get();
        if(count($checkPackage) > 0 || count($checkPromo) > 0){
            $request->session()->flash('error', 'It seems that the record is still being used in other items. Deactivation failed.');
        }else{
            $service = Service::findOrFail($id);
            $service->update([
                'isActive' => 0
            ]);
            $request->session()->flash('success', 'Successfully deactivated.');  
        }
        return Redirect('service');
    }
    
    public function reactivate(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $service->update([
            'isActive' => 1
        ]);
        $request->session()->flash('success', 'Successfully reactivated.');  
        return Redirect('service');
    }
}

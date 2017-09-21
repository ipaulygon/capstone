<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rack;
use Validator;
use Redirect;
use Session;
use DB;
use Illuminate\Validation\Rule;

class RackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $racks = DB::table('rack')
            ->where('isActive',1)
            ->get();
        $deactivate = DB::table('rack')
            ->where('isActive',0)
            ->get();
        return View('rack.index', compact('racks','deactivate'));
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
            'name' => ['required','max:20','unique:rack','regex:/^[^~`!@#*_={}|\;<>,.?]+$/'],
            'description' => 'max:50',
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
            'regex' => 'The :attribute must not contain special characters. (i.e. ~`!@#^*_={}|\;<>,.?).'                
        ];
        $niceNames = [
            'name' => 'Rack',
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
                Rack::create([
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
            return Redirect('rack');
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
        $rack = Rack::findOrFail($id);
        return response()->json(['rack'=>$rack]);
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
            'name' => ['required','max:20',Rule::unique('rack')->ignore(trim($request->id)),'regex:/^[^~`!@#*_={}|\;<>,.?]+$/'],
            'description' => 'max:50',
        ];
        $messages = [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
            'regex' => 'The :attribute must not contain special characters. (i.e. ~`!@#^*_={}|\;<>,.?).'                
        ];
        $niceNames = [
            'name' => 'Rack',
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
                $rack = Rack::findOrFail(trim($request->id));
                $rack->update([
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
            return Redirect('rack');
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
            $checkJob = DB::table('job_header as jh')
                ->join('rack as r','r.id','jh.rackId')
                ->where('jh.rackId',$id)
                ->where('jh.release',null)
                ->get();
            if(count($checkJob) > 0){
                $request->session()->flash('error', 'It seems that the record is still being used in other items. Deactivation failed.');
            }else{
                $rack = Rack::findOrFail($id);
                $rack->update([
                    'isActive' => 0
                ]);
                $request->session()->flash('success', 'Successfully deactivated.');  
            }
            DB::commit();
        }catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            $errMess = $e->getMessage();
            return Redirect::back()->withErrors($errMess);
        }
        return Redirect('rack');
    }

    public function reactivate(Request $request, $id)
    {
        try{
            DB::beginTransaction();
            $rack = Rack::findOrFail($id);
            $rack->update([
                'isActive' => 1
            ]);
            DB::commit();
        }catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            $errMess = $e->getMessage();
            return Redirect::back()->withErrors($errMess);
        }
        $request->session()->flash('success', 'Successfully reactivated.');  
        return Redirect('rack');
    }
}

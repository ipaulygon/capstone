<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Technician;
use Validator;
use Redirect;
use Session;
use DB;
use Illuminate\Validation\Rule;

class TechnicianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $technicians = DB::table('technician')
            ->where('isActive',1)
            ->select('technician.*')
            ->get();
        $deactivate = DB::table('technician')
            ->where('isActive',0)
            ->select('technician.*')
            ->get();
        return View('technician.index',compact('technicians','deactivate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $image = "pics/steve.jpg";
        $date = date('m/d/Y');
        return View('technician.create',compact('image','date'));
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
            'image' => 'image|mimes:jpeg,png,jpg,svg',
            'firstName' => ['required','max:100',Rule::unique('technician')->where('middleName',trim($request->middleName))->where('lastName',trim($request->lastName))],
            'middleName' => 'max:100',
            'lastName' => 'required|max:100',
            'address' => 'required|max:140',
            'contact' => 'required',
            'email' => 'nullable|email|unique:technician',
        ];
        $messages = [
            'firstName.unique' => 'Name is already taken',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'image' => 'Technician Photo',
            'firstName' => 'First Name',
            'middleName' => 'Middle Name',
            'lastName' => 'Last Name',
            'address' => 'Address',
            'contact' => 'Contact No.',
            'email' => 'Email Address',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($request->except('image'));
        }
        else{
            try{
                DB::beginTransaction();
                $file = $request->file('image');
                $techPic = "";
                if($file == '' || $file == null){
                    $techPic = "pics/steve.jpg";
                }else{
                    $date = date("Ymdhis");
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $techPic = "pics/".$date.'.'.$extension;
                    $request->file('image')->move("pics",$techPic);    
                }
                $birthDate = explode('/',$request->birthdate); // MM[0] DD[1] YYYY[2] 
                $finalBirthDate = "$birthDate[2]-$birthDate[0]-$birthDate[1]";
                Technician::create([
                    'firstName' => trim($request->firstName),
                    'middleName' => trim($request->middleName),
                    'lastName' => trim($request->lastName),
                    'address' => trim($request->address),
                    'birthdate' => $finalBirthDate,
                    'contact' => trim($request->contact),
                    'email' => trim($request->email),
                    'image' => $techPic,
                    'isActive' => 1
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
        $technician = Technician::findOrFail($id);
        $birthDate = explode('-',$technician->birthdate);
        $date = "$birthDate[1]/$birthDate[2]/$birthDate[0]";
        $image = $technician->image;
        return View('technician.edit',compact('technician','date','image'));
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
            'image' => 'image|mimes:jpeg,png,jpg,svg',
            'firstName' => ['required','max:100',Rule::unique('technician')->where('middleName',trim($request->middleName))->where('lastName',trim($request->lastName))->ignore($id)],
            'middleName' => 'max:100',
            'lastName' => 'required|max:100',
            'address' => 'required|max:140',
            'contact' => 'required',
            'email' => ['nullable','email',Rule::unique('technician')->ignore($id)],
        ];
        $messages = [
            'firstName.unique' => 'Name is already taken',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'image' => 'Technician Photo',
            'firstName' => 'First Name',
            'middleName' => 'Middle Name',
            'lastName' => 'Last Name',
            'address' => 'Address',
            'contact' => 'Contact No.',
            'email' => 'Email Address',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($request->except('image'));
        }
        else{
            try{
                DB::beginTransaction();
                $technician = Technician::findOrFail($id);
                $file = $request->file('image');
                $techPic = "";
                if($file == '' || $file == null){
                    $techPic = $technician->image;
                }else{
                    $date = date("Ymdhis");
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $techPic = "pics/".$date.'.'.$extension;
                    $request->file('image')->move("pics",$techPic);    
                }
                $birthDate = explode('/',$request->birthdate); // MM[0] DD[1] YYYY[2] 
                $finalBirthDate = "$birthDate[2]-$birthDate[0]-$birthDate[1]";
                $technician->update([
                    'firstName' => trim($request->firstName),
                    'middleName' => trim($request->middleName),
                    'lastName' => trim($request->lastName),
                    'address' => trim($request->address),
                    'birthdate' => $finalBirthDate,
                    'contact' => trim($request->contact),
                    'email' => trim($request->email),
                    'image' => $techPic,
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
        $technician = Technician::findOrFail($id);
        $technician->update([
            'isActive' => 0
        ]);
        $request->session()->flash('success', 'Successfully deactivated.');  
        return Redirect::back();
    }
    
    public function reactivate(Request $request,$id)
    {
        $technician = Technician::findOrFail($id);
        $technician->update([
            'isActive' => 1
        ]);
        $request->session()->flash('success', 'Successfully reactivated.');  
        return Redirect::back();
    }
}
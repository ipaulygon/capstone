<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Vehicle;
use App\Customer;
use Validator;
use Redirect;
use Response;
use Session;
use DB;
use Illuminate\Validation\Rule;

class EstimateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = DB::table('customer')
            ->select('customer.*')
            ->get();
        $models = DB::table('vehicle_model as vd')
            ->join('vehicle_make as vk','vd.makeId','vk.id')
            ->select('vd.*','vk.name as make')
            ->get();
        $technicians = DB::table('technician')
            ->where('isActive',1)
            ->select('technician.*')
            ->get();
        return View('estimate.index',compact('customers','models','technicians'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

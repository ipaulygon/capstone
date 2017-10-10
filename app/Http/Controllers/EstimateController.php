<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\EstimateHeader;
use App\EstimateProduct;
use App\EstimateService;
use App\EstimatePackage;
use App\EstimatePromo;
use App\EstimateDiscount;
use App\EstimateTechnician;
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
        $estimates = DB::table('estimate_header as e')
            ->join('customer as c','c.id','e.customerId')
            ->join('vehicle as v','v.id','e.vehicleId')
            ->join('vehicle_model as vd','vd.id','v.modelId')
            ->join('vehicle_make as vk','vk.id','vd.makeId')
            ->select('e.*','e.id as estimateId','c.*','v.*','vd.name as model','vd.year as year','v.isManual as transmission','vk.name as make')
            ->get();
        return View('estimate.index',compact('estimates'));
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
        return View('layouts.404');
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
        return View('layouts.404');
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
        return View('layouts.404');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return View('layouts.404');
    }
}

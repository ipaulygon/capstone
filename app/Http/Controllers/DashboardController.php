<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use Redirect;
use Session;
use DB;
use Illuminate\Validation\Rule;

use App\JobHeader;
use App\SalesHeader;
use App\User;
use App\Technician;
use Auth;
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(Auth::id());
        if($user->type==1){
            $stocks = DB::table('inventory as i')
                ->join('product as p','p.id','i.productId')
                ->join('product_type as pt','pt.id','p.typeId')
                ->join('product_brand as pb','pb.id','p.brandId')
                ->join('product_variance as pv','pv.id','p.varianceId')
                ->where('p.isActive',1)
                ->whereColumn('i.quantity','<=','p.reorder')
                ->select('i.*','p.reorder as reorder','p.name as product','p.isOriginal as isOriginal','pt.name as type','pb.name as brand','pv.name as variance')
                ->get();
            $pendingJobs = JobHeader::where('isComplete',0)->get();
            $jobs = JobHeader::get();
            $sales = SalesHeader::get();
            return View('dashboard',compact('stocks','jobs','sales','pendingJobs'));
        }else{
            $id = str_replace('TECH-','',$user->name);
            $id = (int)$id;
            $techUser = Technician::find($id);
            $pendingJobs = JobHeader::where('isComplete',0)->get();
            return View('dashboard',compact('pendingJobs'));
        }
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

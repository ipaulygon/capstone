<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $data = DB::table('product as p')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->join('inventory as i','p.id','i.productId')
            ->select('p.*','pt.name as type','pb.name as brand','pv.name as variance','i.quantity as quantity','p.name as name')
            ->get(['type','quantity','name','brand']);
        // $results = DB::select( DB::raw("select * from product join inventory on product.id = inventory.productId JOIN product_type on product.typeId = product_type.id JOIN product_brand on product.brandId = product_brand.id JOIN product_variance on product.varianceId = product_variance.id") );

        $services = DB::table('service as s')
            ->join('service_category as c','c.id','s.categoryId')
            ->where('s.isActive',1)
            ->select('s.*','s.name as name','c.name as category','s.size as size')
            ->get();

        $jobs = DB::table('job_header as j')
            ->join('customer as c','c.id','j.customerId')
            ->join('vehicle as v','v.id','j.vehicleId')
            ->join('vehicle_model as vd','vd.id','v.modelId')
            ->join('vehicle_make as vk','vk.id','vd.makeId')
            ->join('job_product as jp','j.id','jp.jobId')
            ->join('product as p','p.id','jp.productId')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->join('job_service as js','j.id','js.jobId')
            ->join('service as s','s.id','js.serviceId')
            ->join('service_category as sc','sc.id','s.categoryId')
            ->select('j.*','j.id as jobId','c.*','v.*','vd.name as model','vd.year as year','vk.name as make','p.*','pt.name as type','pb.name as brand','pv.name as variance','s.*','s.name as name','sc.name as category','s.size as size')
            ->get();
        return view('report.index',compact('data','services','jobs'));
       
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


    public function report($id)
    {
       
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

     public function where(Request $request)
     {
        $id = $request->id;
        $data = DB::table('product as p')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->join('inventory as i','p.id','i.productId')
            ->where('i.updated_at',$id)
            ->select('p.*','pt.name as type','pb.name as brand','pv.name as variance','i.quantity as quantity','p.name as name')
            ->get(['type','quantity','name','brand']);
             return response()->json(['data'=>$data]);
        // $results = DB::select( DB::raw("select * from product join inventory on product.id = inventory.productId JOIN product_type on product.typeId = product_type.id JOIN product_brand on product.brandId = product_brand.id JOIN product_variance on product.varianceId = product_variance.id") );

        $services = DB::table('service as s')
            ->join('service_category as c','c.id','s.categoryId')
            ->where('s.isActive',1)
            ->select('s.*','s.name as name','c.name as category','s.size as size')
            ->get();
             return response()->json(['services'=>$services]);

        $jobs = DB::table('job_header as j')
            ->join('customer as c','c.id','j.customerId')
            ->join('vehicle as v','v.id','j.vehicleId')
            ->join('vehicle_model as vd','vd.id','v.modelId')
            ->join('vehicle_make as vk','vk.id','vd.makeId')
            ->join('job_product as jp','j.id','jp.jobId')
            ->join('product as p','p.id','jp.productId')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->join('job_service as js','j.id','js.jobId')
            ->join('service as s','s.id','js.serviceId')
            ->join('service_category as sc','sc.id','s.categoryId')
            ->select('j.*','j.id as jobId','c.*','v.*','vd.name as model','vd.year as year','vk.name as make','p.*','pt.name as type','pb.name as brand','pv.name as variance','s.*','s.name as name','sc.name as category','s.size as size')
            ->get();
             return response()->json(['jobs'=>$jobs]);
        return view('report.index',compact('data','services','jobs'));
     }
}

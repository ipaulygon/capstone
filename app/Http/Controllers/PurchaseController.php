<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\PurchaseHeader;
use App\PurchaseDetail;
use App\Product;
use Validator;
use Redirect;
use Session;
use DB;
use Illuminate\Validation\Rule;
class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchases = DB::table('purchase_header as p')
            ->join('supplier as s','s.id','p.supplierId')
            ->where('p.isActive',1)
            ->select('p.*','s.name as supplier')
            ->get();
        return View('purchase.index',compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = DB::table('supplier')
            ->where('isActive',1)
            ->get();
        $products = DB::table('product as p')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            // ->join('product_vehicle as v','v.productId','p.id')
            // ->join('vehicle_model as vd','vd.id','v.modelId')
            // ->join('vehicle_make as vk','vk.id','vd.makeId')
            ->where('p.isActive',1)
            ->select('p.*','pt.name as type','pb.name as brand','pv.name as variance')
            ->get();
        $date = date('F j, Y');
        return View('purchase.create',compact('products','suppliers','date'));
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
            'supplierId' => 'required',
            'remarks' => 'max:200',
            'product.*' => 'required',
            'qty.*' => 'required|integer|between:0,100',
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
        ];
        $niceNames = [
            'supplierId' => 'Supplier',
            'remarks' => 'Remarks',
            'product.*' => 'Product',
            'qty.*' => 'Quantity'
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                $id = PurchaseHeader::all()->count() + 1;
                $id = 'ORDER'.str_pad($id, 5, '0', STR_PAD_LEFT); 
                PurchaseHeader::create([
                    'id' => $id,
                    'supplierId' => $request->supplierId,
                    'remarks' => trim($request->remarks),
                    'isActive' => 1,
                    'isFinalize' => 0,
                    'isDelivered' => 0
                ]);
                $purchase = PurchaseHeader::all()->last();
                $products = $request->product;
                $qtys = $request->qty;
                $models = $request->modelId;
                foreach($products as $key=>$product){
                    PurchaseDetail::create([
                        'purchaseId' => $purchase->id,
                        'productId' => $product,
                        'modelId' => $models[$key],
                        'quantity' => $qtys[$key],
                        'delivered' => 0,
                        'isActive'=> 1
                    ]);
                }
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
        $purchase = PurchaseHeader::findOrFail($id);
        if($purchase->isFinalize){
            return View('layouts.404');
        }
        $suppliers = DB::table('supplier')
            ->where('isActive',1)
            ->get();
        $products = DB::table('product as p')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            // ->join('product_vehicle as v','v.productId','p.id')
            // ->join('vehicle_model as vd','vd.id','v.modelId')
            // ->join('vehicle_make as vk','vk.id','vd.makeId')
            ->where('p.isActive',1)
            ->select('p.*','pt.name as type','pb.name as brand','pv.name as variance')
            ->get();
        $date = date('F j, Y');
        return View('purchase.edit',compact('purchase','suppliers','products','date'));
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
            'supplierId' => 'required',
            'remarks' => 'max:200',
            'product.*' => 'required',
            'qty.*' => 'required|integer|between:0,100',
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
        ];
        $niceNames = [
            'supplierId' => 'Supplier',
            'remarks' => 'Remarks',
            'product.*' => 'Product',
            'qty.*' => 'Quantity'
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        else{
            try{
                DB::beginTransaction();
                $purchase = PurchaseHeader::findOrFail($id);
                $purchase->update([
                    'supplierId' => $request->supplierId,
                    'remarks' => trim($request->remarks),
                ]);
                $products = $request->product;
                $qtys = $request->qty;
                $models = $request->modelId;
                PurchaseDetail::where('purchaseId',''.$id)->update(['isActive'=>0]);
                foreach($products as $key=>$product){
                    PurchaseDetail::updateOrCreate(
                        ['purchaseId' => $id,'productId' => $product],
                        ['modelId' => $models[$key],'quantity' => $qtys[$key],'isActive'=> 1]
                    );
                }
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
        $purchase = PurchaseHeader::findOrFail($id);
        $purchase->update([
            'isActive' => 0
        ]);
        $request->session()->flash('success', 'Successfully deactivated.');  
        return Redirect::back();
    }

    public function finalize(Request $request, $id){
        $purchase = PurchaseHeader::findOrFail($id);
        $purchase->update([
            'isFinalize' => 1
        ]);
        $request->session()->flash('success', 'Successfully finalized.');  
        return Redirect::back();
    }

    public function product($id){
        $product = Product::with('type')->with('brand')->with('variance')->with('vehicle.model.make')->findOrFail($id);
        return response()->json(['product'=>$product]);
    }
}

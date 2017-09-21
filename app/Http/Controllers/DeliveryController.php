<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\DeliveryHeader;
use App\DeliveryDetail;
use App\DeliveryOrder;
use App\PurchaseHeader;
use App\PurchaseDetail;
use App\Inventory;
use Validator;
use Redirect;
use Session;
use DB;
use Illuminate\Validation\Rule;
class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deliveries = DeliveryHeader::where('isActive',1)->get();
        return View('delivery.index',compact('deliveries'));
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
        $date = date('m/d/Y');
        return View('delivery.create',compact('suppliers','date'));
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
            'product' => 'required',
            'qty.*' => 'required|integer|between:0,10000',
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
        ];
        $niceNames = [
            'supplierId' => 'Supplier',
            'product' => 'Product',
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
                $id = DeliveryHeader::all()->count() + 1;
                $id = 'DELIVERY'.str_pad($id, 5, '0', STR_PAD_LEFT); 
                $created = explode('/',$request->date); // MM[0] DD[1] YYYY[2] 
                $finalCreated = "$created[2]-$created[0]-$created[1]";
                $delivery = DeliveryHeader::create([
                    'id' => $id,
                    'supplierId' => $request->supplierId,
                    'dateMake' => $finalCreated
                ]);
                $products = $request->product;
                $qtys = $request->qty;
                $orders = $request->order;
                sort($orders);
                foreach($products as $key=>$product){
                    if($qtys[$key]!=0){
                        DeliveryDetail::create([
                            'deliveryId' => $delivery->id,
                            'productId' => $product,
                            'quantity' => $qtys[$key],
                        ]);
                        $inventory = Inventory::where('productId',$product)->first();
                        $inventory->increment('quantity', $qtys[$key]);
                    }
                }
                foreach($orders as $order){
                    DeliveryOrder::create([
                        'purchaseId' => $order,
                        'deliveryId' => $id
                    ]);
                    foreach($products as $key=>$product){
                        if($qtys[$key]!=0){
                            $detail = PurchaseDetail::where('purchaseId',''.$order)->where('productId',$product)->where('isActive',1)->first();
                            if(!empty($detail)){
                                $qty = $detail->quantity;
                                $delivered = $detail->delivered;
                                if($qty != $delivered){
                                    while($qty!=$delivered && $qtys[$key]!=0){
                                        $delivered++;
                                        $qtys[$key]--;
                                    }
                                    $detail->update([
                                        'delivered' => $delivered
                                    ]);
                                }
                            }
                        }
                    }
                    $details = PurchaseDetail::where('purchaseId',''.$order)->where('isActive',1)->get();
                    foreach($details as $detail){
                        if($detail->quantity!=$detail->delivered){
                            $delivery = false;
                        }
                    }
                    if($delivery){
                        PurchaseHeader::where('id',''.$order)->update(['isDelivered'=>1]);
                    }
                }
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors($errMess);
            }
            $request->session()->flash('success', 'Successfully added.');
            return Redirect('delivery');
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
    public function edit(Request $request,$id)
    {
        $delivery = DeliveryHeader::findOrFail($id);
        $suppliers = DB::table('supplier')
        ->where('isActive',1)
        ->get();
        $date = date('m/d/Y',strtotime($delivery->dateMake));
        if($request->session()->has('admin')){
            return View('delivery.edit',compact('delivery','suppliers','date'));
        }else{
            $request->session()->flash('error', 'Unauthorized access.');
            return Redirect('delivery');
        }
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
            'product' => 'required',
            'qty.*' => 'required|integer|between:0,10000',
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
        ];
        $niceNames = [
            'supplierId' => 'Supplier',
            'product' => 'Product',
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
                $delivery = DeliveryHeader::findOrFail($id);
                $created = explode('/',$request->date); // MM[0] DD[1] YYYY[2] 
                $finalCreated = "$created[2]-$created[0]-$created[1]";
                $delivery->update([
                    'supplierId' => $request->supplierId,
                    'dateMake' => $finalCreated
                ]);
                $products = $request->product;
                $qtys = $request->qty;
                $orders = $request->order;
                sort($orders);
                foreach($products as $key=>$product){
                    $inventory = Inventory::where('productId',$product)->first();
                    $detail = DeliveryDetail::where('deliveryId',$delivery->id)->where('productId',$product)->first();
                    $inventory->decrement('quantity',$detail->quantity);
                    $detail->update([
                        'deliveryId' => $delivery->id,
                        'productId' => $product,
                        'quantity' => $qtys[$key],
                    ]);
                    $inventory->increment('quantity', $qtys[$key]);
                    if($inventory->quantity<0){
                        $request->session()->flash('error', 'Insufficient inventory resources. Check your inventory status.');
                        return Redirect::back()->withInput();
                    }
                }
                foreach($orders as $order){
                    foreach($products as $key=>$product){
                        $detail = PurchaseDetail::where('purchaseId',''.$order)->where('productId',$product)->where('isActive',1)->first();
                        $detail->update([
                            'delivered' => 0
                        ]);
                        if(!empty($detail)){
                            $qty = $detail->quantity;
                            $delivered = $detail->delivered;
                            if($qty != $delivered){
                                while($qty!=$delivered && $qtys[$key]!=0){
                                    $delivered++;
                                    $qtys[$key]--;
                                }
                                $detail->update([
                                    'delivered' => $delivered
                                ]);
                            }
                        }
                    }
                    $details = PurchaseDetail::where('purchaseId',''.$order)->where('isActive',1)->get();
                    foreach($details as $detail){
                        if($detail->quantity!=$detail->delivered){
                            $delivery = false;
                        }
                    }
                    if($delivery){
                        PurchaseHeader::where('id',''.$order)->update(['isDelivered'=>1]);
                    }
                }
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors($errMess);
            }
            $request->session()->flash('success', 'Successfully updated.');
            return Redirect('delivery');
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
        return View('layouts.404');
    }

    public function header($id)
    {
        $purchases = DB::table('purchase_header as p')
            ->where('isFinalize',1)
            ->where('isDelivered',0)
            ->where('supplierId',$id)
            ->select('p.*')
            ->get();
        return response()->json(['purchases'=>$purchases]);
    }
    
    public function headerReturn($id)
    {
        $purchases = DB::table('purchase_header as p')
            ->where('isFinalize',1)
            ->where('supplierId',$id)
            ->select('p.*')
            ->get();
        return response()->json(['purchases'=>$purchases]);
    }
    
    public function detail($id)
    {
        $products = DB::table('purchase_detail as pd')
            ->join('product as p','p.id','pd.productId')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->where('p.isActive',1)
            ->where('pd.isActive',1)
            ->where('pd.purchaseId',''.$id)
            ->select('pd.*','p.name as product','p.isOriginal as original','pt.name as type','pb.name as brand','pv.name as variance')
            ->get();
        return response()->json(['products'=>$products]);
    }
}

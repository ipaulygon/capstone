<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\ReturnHeader;
use App\ReturnDetail;
use App\ReturnDelivery;
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
use App\Audit;
use Auth;
use Illuminate\Validation\Rule;
class ReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $returns = DB::table('return_header as r')
            ->join('supplier as s','s.id','r.supplierId')
            ->where('r.isActive',1)
            ->select('r.*','s.name as supplier')
            ->get();
        return View('return.index',compact('returns'));
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
        return View('return.create',compact('suppliers','date'));
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
            'delivery.*' => 'required',
            'qty.*' => 'required|integer|between:0,10000',
            'remarks' => 'max:200'
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
        ];
        $niceNames = [
            'supplierId' => 'Supplier',
            'product' => 'Product',
            'delivery.*' => 'Delivery',
            'qty.*' => 'Quantity',
            'remarks' => 'Remarks'
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                $id = ReturnHeader::all()->count() + 1;
                $id = 'RETURN'.str_pad($id, 5, '0', STR_PAD_LEFT); 
                $created = explode('/',$request->date); // MM[0] DD[1] YYYY[2] 
                $finalCreated = "$created[2]-$created[0]-$created[1]";
                $return = ReturnHeader::create([
                    'id' => $id,
                    'supplierId' => $request->supplierId,
                    'dateMake' => $finalCreated,
                    'remarks' => $request->remarks
                ]);
                $products = $request->product;
                $deliverys = $request->delivery;
                $qtys = $request->qty;
                $orders = $request->order;
                sort($orders);
                foreach($products as $key=>$product){
                    if($qtys[$key]!=0){
                        ReturnDetail::create([
                            'returnId' => $return->id,
                            'productId' => $product,
                            'deliveryId' => $deliverys[$key],
                            'quantity' => $qtys[$key],
                        ]);
                        $deliveryDetail = DeliveryDetail::where('productId',$product)->where('deliveryId',$deliverys[$key])->first();
                        $deliveryDetail->increment('returned',$qtys[$key]);
                        $inventory = Inventory::where('productId',$product)->first();
                        if($inventory->quantity<$qtys[$key]){
                            $request->session()->flash('error', 'Insufficient inventory resources. Check your inventory status.');
                            return Redirect::back()->withInput();
                        }
                        $inventory->decrement('quantity', $qtys[$key]);
                    }
                }
                foreach($orders as $order){
                    $ordered = DeliveryOrder::where('deliveryId',$order)->get();
                    foreach($ordered as $ordered){
                        foreach($products as $key=>$product){
                            $detail = PurchaseDetail::where('purchaseId',''.$ordered->purchaseId)->where('productId',$product)->where('isActive',1)->first();
                            if(!empty($detail)){
                                $qty = $detail->quantity;
                                $delivered = $detail->delivered;
                                if($delivered!=0){
                                    while($delivered!=0 && $qtys[$key]!=0){
                                        $delivered--;
                                        $qtys[$key]--;
                                    }
                                    $detail->update([
                                        'delivered' => $delivered
                                    ]);
                                }
                            }
                        }
                        $details = PurchaseDetail::where('purchaseId',''.$ordered->purchaseId)->where('isActive',1)->get();
                        $delivery = true;
                        foreach($details as $detail){
                            if($detail->quantity!=$detail->delivered){
                                $delivery = false;
                            }
                        }
                        if($delivery){
                            PurchaseHeader::where('id',''.$ordered->purchaseId)->update(['isDelivered'=>1]);
                        }else{
                            PurchaseHeader::where('id',''.$ordered->purchaseId)->update(['isDelivered'=>0]);
                        }
                    }
                    ReturnDelivery::create([
                        'returnId' => $return->id,
                        'deliveryId' => $order
                    ]);
                }
                Audit::create([
                    'userId' => Auth::id(),
                    'name' => "Return Items",
                    'json' => json_encode($request->all())
                ]);
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors($errMess);
            }
            $request->session()->flash('success', 'Successfully added.');
            return Redirect('return');
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

    public function header($id){
        $deliveries = DB::table('delivery_header as d')
            ->where('supplierId',$id)
            ->where('isReceived',1)
            ->select('d.*')
            ->get();
        return response()->json(['deliveries'=>$deliveries]);
    }

    public function detail($id){
        $products = DB::table('delivery_detail as dd')
            ->join('product as p','p.id','dd.productId')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->where('p.isActive',1)
            ->where('dd.isActive',1)
            ->where('dd.deliveryId',''.$id)
            ->select('dd.*','p.name as product','p.isOriginal as original','pt.name as type','pb.name as brand','pv.name as variance')
            ->get();
        return response()->json(['products'=>$products]);
    }
}

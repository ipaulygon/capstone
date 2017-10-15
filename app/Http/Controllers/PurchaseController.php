<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\PurchaseHeader;
use App\PurchaseDetail;
use App\DeliveryOrder;
use Validator;
use Redirect;
use Session;
use DB;
use App\Audit;
use Auth;
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
        $dpurchases = DB::table('purchase_header as p')
            ->join('supplier as s','s.id','p.supplierId')
            ->where('p.isActive',0)
            ->select('p.*','s.name as supplier')
            ->get();
        return View('purchase.index',compact('purchases','dpurchases'));
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
            ->where('p.isActive',1)
            ->select('p.*','pt.name as type','pb.name as brand','pv.name as variance')
            ->get();
        $date = date('m/d/Y');
        $created = date('Y-m-d H:i:s');
        return View('purchase.create',compact('products','suppliers','date','created'));
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
            'product' => 'required',
            'price.*' => 'required|between:0,500000',
            'qty.*' => 'required|integer',
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
        ];
        $niceNames = [
            'supplierId' => 'Supplier',
            'remarks' => 'Remarks',
            'product' => 'Product',
            'price.*' => 'Price',
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
                $created = explode('/',$request->date); // MM[0] DD[1] YYYY[2] 
                $finalCreated = "$created[2]-$created[0]-$created[1]";
                $purchase = PurchaseHeader::create([
                    'id' => $id,
                    'supplierId' => $request->supplierId,
                    'remarks' => trim($request->remarks),
                    'dateMake' => $finalCreated,
                    'isFinalize' => 0,
                    'isDelivered' => 0,
                ]);
                $products = $request->product;
                $qtys = $request->qty;
                $prices = $request->price;
                $models = $request->modelId;
                foreach($products as $key=>$product){
                    if($qtys[$key]!=0){
                        if($models[$key]!=null){
                            $model = explode(',',$models[$key]);
                        }else{
                            $model[0] = null;
                            $model[1] = null;
                        }
                        PurchaseDetail::create([
                            'purchaseId' => $purchase->id,
                            'productId' => $product,
                            'modelId' => $model[0],
                            'isManual' => $model[1],
                            'quantity' => $qtys[$key],
                            'price' => str_replace(',','',$prices[$key]),
                            'delivered' => 0,
                            'isActive'=> 1
                        ]);
                    }
                }
                Audit::create([
                    'userId' => Auth::id(),
                    'name' => "Create Purchase Order",
                    'json' => json_encode($request->all())
                ]);
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors($errMess);
            }
            $request->session()->flash('success', 'Successfully added.');
            return Redirect('purchase');
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
        $purchase = PurchaseHeader::findOrFail($id);
        $suppliers = DB::table('supplier')
            ->where('isActive',1)
            ->get();
        $products = DB::table('product as p')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->where('p.isActive',1)
            ->select('p.*','pt.name as type','pb.name as brand','pv.name as variance')
            ->get();
        $date = date('m/d/Y',strtotime($purchase->dateMake));
        $created = $purchase->created_at;
        if($request->session()->has('admin') || !$purchase->isFinalize){
            return View('purchase.edit',compact('purchase','suppliers','products','date','created'));
        }else{
            $request->session()->flash('error', 'Unauthorized access.');
            return Redirect('purchase');
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
            'remarks' => 'max:200',
            'product' => 'required',
            'price.*' => 'required|between:0,500000',
            'qty.*' => 'required|integer',
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
        ];
        $niceNames = [
            'supplierId' => 'Supplier',
            'remarks' => 'Remarks',
            'product' => 'Product',
            'price.*' => 'Price',
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
                $purchase = PurchaseHeader::findOrFail($id);
                $created = explode('/',$request->date); // MM[0] DD[1] YYYY[2] 
                $finalCreated = "$created[2]-$created[0]-$created[1]";
                $purchase->update([
                    'supplierId' => $request->supplierId,
                    'remarks' => trim($request->remarks),
                    'dateMake' => $finalCreated
                ]);
                $products = $request->product;
                $qtys = $request->qty;
                $prices = $request->price;
                $models = $request->modelId;
                PurchaseDetail::where('purchaseId',''.$id)->update(['isActive'=>0]);
                foreach($products as $key=>$product){
                    if($models[$key]!=null){
                        $model = explode(',',$models[$key]);
                    }else{
                        $model[0] = null;
                        $model[1] = null;
                    }
                    PurchaseDetail::updateOrCreate(
                        ['purchaseId' => $id,'productId' => $product],
                        ['modelId' => $model[0],
                        'isManual' => $model[1],
                        'quantity' => $qtys[$key],
                        'price' => str_replace(',','',$prices[$key]),
                        'isActive'=> 1]
                    );
                }
                Audit::create([
                    'userId' => Auth::id(),
                    'name' => "Update Purchase Order",
                    'json' => json_encode($request->all())
                ]);
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors($errMess);
            }
            $request->session()->flash('success', 'Successfully updated.');
            return Redirect('purchase');
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
            $checkOrder = DB::table('delivery_order')
                ->where('purchaseId',$id)
                ->select('delivery_order.*')
                ->get();
            if(count($checkOrder) > 0){
                $request->session()->flash('error', 'It seems that the record is still being used in other items. Deactivation failed.');
            }else{
                $purchase = PurchaseHeader::findOrFail($id);
                $purchase->update([
                    'isActive' => 0
                ]);
                PurchaseDetail::where('purchaseId',''.$id)->update(['isActive'=>0]);
                Audit::create([
                    'userId' => Auth::id(),
                    'name' => "Deactivate Purchase Order",
                    'json' => json_encode($request->all())
                ]);
                $request->session()->flash('success', 'Successfully deactivated.');  
            }
            DB::commit();
        }catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            $errMess = $e->getMessage();
            return Redirect::back()->withErrors($errMess);
        }
        return Redirect('purchase');
    }

    public function reactivate(Request $request, $id)
    {
        try{
            DB::beginTransaction();
            $purchase = PurchaseHeader::findOrFail($id);
            $purchase->update([
                'isActive' => 1
            ]);
            Audit::create([
                'userId' => Auth::id(),
                'name' => "Reactivate Purchase Order",
                'json' => json_encode($request->all())
            ]);
            DB::commit();
        }catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            $errMess = $e->getMessage();
            return Redirect::back()->withErrors($errMess);
        }
        $request->session()->flash('success', 'Successfully reactivated.');  
        return Redirect('purchase');
    }

    public function finalize(Request $request, $id){
        try{
            DB::beginTransaction();
            $purchase = PurchaseHeader::findOrFail($id);
            $purchase->update([
                'isFinalize' => 1
            ]);
            Audit::create([
                'userId' => Auth::id(),
                'name' => "Finalize Purchase Order",
                'json' => json_encode($request->all())
            ]);
            DB::commit();
        }catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            $errMess = $e->getMessage();
            return Redirect::back()->withErrors($errMess);
        }
        $request->session()->flash('success', 'Successfully finalized.');  
        return Redirect('purchase');
    }

    public function finalz($id){
        $purchase = PurchaseHeader::with('detail')->findOrFail($id);
        return response()->json(['purchase'=>$purchase]);
    }
}

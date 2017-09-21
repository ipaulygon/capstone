@extends('layouts.master')

@section('title')
    {{"Point of Sales"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
@stop

@section('content')
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"></h3>
                <div class="box-tools pull-right">
                    <a href="{{ URL::to('/sales/create') }}" class="btn btn-success btn-md">
                    <i class="glyphicon glyphicon-plus"></i> New Record</a>
                </div>
            </div>
            <div class="box-body dataTable_wrapper">
                <table id="list" class="table table-striped table-bordered responsive">
                    <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Description</th>
                            <th class="text-right">Price (PhP)</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                            <tr>
                                <?php 
                                    $salesId = 'INV'.str_pad($sale->id, 5, '0', STR_PAD_LEFT); 
                                ?>
                                <td>{{$salesId}}</td>
                                <td>
                                    @foreach($sale->product as $product)
                                         <?php
                                            if($product->product->isOriginal!=null){
                                                $type = ($product->product->isOriginal=="type1" ? $util->type1 : $util->type2);
                                            }else{
                                                $type = "";
                                            }
                                        ?>
                                        <li>{{$product->product->brand->name}} - {{$product->product->name}} {{$type}} ({{$product->product->variance->name}}) x {{$product->quantity}} pcs.</li>
                                    @endforeach
                                    @foreach($sale->package as $package)
                                        <li>{{$package->package->name}}  x {{$package->quantity}} pcs.</li>
                                    @endforeach
                                    @foreach($sale->promo as $promo)
                                        <li>{{$promo->promo->name}}  x {{$promo->quantity}} pcs.</li>
                                    @endforeach
                                    @if($sale->discount)
                                        <li>{{$sale->discount->discount->name}} Discount</li>
                                    @endif
                                </td>
                                <td class="text-right">{{number_format($sale->total,2)}}</td>
                                <td>{{date('F j, Y - H:i:s',strtotime($sale->created_at))}}</td>
                                <td class="text-right">
                                    <a href="{{url('/sales/pdf/'.$sale->id)}}" target="_blank" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Generate PDF">
                                        <i class="glyphicon glyphicon-file"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{ URL::asset('assets/datatables/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#list').DataTable({
                responsive: true,
            });
            $('#tSales').addClass('active');
        });
    </script>
@stop
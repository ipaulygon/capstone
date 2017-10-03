@extends('layouts.master')

@section('title')
    {{"Warranty Record"}}
@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @if(count($warranties)==0)
                <center><h3>NO WARRANTY FOUND</h3></center>
            @else
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Obtained Warranties</h3>
                    </div>
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table id="salesTable" class="table table-striped table-bordered responsive">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Date</th>
                                        <th>Item</th>
                                        <th>From(Package/Promo)</th>
                                        <th class="text-right">Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($warranties as $w)
                                    <tr>
                                        <td>{{'WS'.str_pad($w->id, 5, '0', STR_PAD_LEFT)}}</td>
                                        <td>{{date('F j, Y H:i:s',strtotime($w->created_at))}}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @foreach($w->product as $product)
                                        <?php
                                            if($product->product->isOriginal!=null){
                                                $type = ($product->product->isOriginal=="type1" ? $util->type1 : $util->type2);
                                            }else{
                                                $type = "";
                                            }
                                        ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>{{$product->product->brand->name}} - {{$product->product->name}} {{$type}} ({{$product->product->variance->name}})</td>
                                            <td></td>
                                            <td class="text-right">{{number_format($product->quantity)}}</td>
                                        </tr>
                                    @endforeach
                                    @foreach($w->package as $product)
                                        <?php
                                            if($product->product->isOriginal!=null){
                                                $type = ($product->product->isOriginal=="type1" ? $util->type1 : $util->type2);
                                            }else{
                                                $type = "";
                                            }
                                        ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>{{$product->product->brand->name}} - {{$product->product->name}} {{$type}} ({{$product->product->variance->name}})</td>
                                            <td>{{$product->sales->package->name}}</td>
                                            <td class="text-right">{{number_format($product->quantity)}}</td>
                                        </tr>
                                    @endforeach
                                    @foreach($w->promo as $product)
                                        <?php
                                            if($product->product->isOriginal!=null){
                                                $type = ($product->product->isOriginal=="type1" ? $util->type1 : $util->type2);
                                            }else{
                                                $type = "";
                                            }
                                        ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>{{$product->product->brand->name}} - {{$product->product->name}} {{$type}} ({{$product->product->variance->name}})</td>
                                            <td>{{$product->sales->promo->name}}</td>
                                            <td class="text-right">{{number_format($product->quantity)}}</td>
                                        </tr>
                                    @endforeach
                                @endforeach 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
<script src="{{ URL::asset('assets/datatables/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#salesTable').DataTable({
                'responsive': true,
                "ordering": false,
                "searching": false,
                "paging": false,
                "info": false,
                "retrieve": true,
            });
            $('#tWarranty').addClass('active');
        });
    </script>
@endsection
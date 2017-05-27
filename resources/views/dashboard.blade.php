@extends('layouts.master')

@section('title')
    {{"Dashboard"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
@stop

@section('content')
    <div class="col-md-12">
        {{-- Jobs --}}
        <div class="col-md-7">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Jobs</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">

                </div>
            </div>
        </div>
        {{-- end of component --}}
        {{-- Reorder --}}
        <div class="col-md-5">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Reorder</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body dataTable_wrapper">
                    <table id="list" class="table table-striped responsive">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-right">Stock</th>
                                <th class="text-right">Threshold</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $product)
                            <tr>
                                <?php
                                    if($product->original!=null){
                                        $part = "- ".$product->original;
                                    }else{
                                        $part = "";
                                    }
                                ?>
                                <td>{{$product->brand}} - {{$product->product}} {{$part}} ({{$product->variance}})</td>
                                <td class="text-right">{{number_format($product->quantity)}}</td>
                                <td class="text-right">{{number_format($product->reorder)}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
        {{-- end of component --}}
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
        });
    </script>
@stop
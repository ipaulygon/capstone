@extends('layouts.master')

@section('title')
    {{"Promo"}}
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
                    <a href="{{ URL::to('/promo/create') }}" class="btn btn-success btn-md">
                    <i class="glyphicon glyphicon-plus"></i> New Record</a>
                </div>
            </div>
            <div class="box-body dataTable_wrapper">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="activeTable">
                        <table id="list" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>Promo</th>
                                    <th class="text-right">Price (PhP)</th>
                                    <th>Products & Services</th>
                                    <th>Free</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($promos as $promo)
                                    <tr>
                                        <td>{{$promo->name}}</td>
                                        <td class="text-right">{{number_format($promo->price,2)}}</td>
                                        <td>
                                            @if($promo->product->isNotEmpty())
                                                <b>Products:</b>
                                            @endif
                                            @foreach($promo->product as $product)
                                                <li>{{$product->product->brand->name}} - {{$product->product->name}} - {{$product->product->isOriginal}} ({{$product->product->variance->name}}) x {{$product->quantity}} pcs.</li>
                                            @endforeach
                                            @if($promo->service->isNotEmpty())
                                                <b>Services:</b>
                                            @endif
                                            @foreach($promo->service as $service)
                                                <li>{{$service->service->name}} - {{$service->service->size}}</li>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($promo->freeProduct as $product)
                                                <li>{{$product->product->brand->name}} - {{$product->product->name}} - {{$product->product->isOriginal}} ({{$product->product->variance->name}}) x {{$product->quantity}} pcs.</li>
                                            @endforeach
                                            @foreach($promo->freeService as $service)
                                                <li>{{$service->service->name}} - {{$service->service->size}}</li>
                                            @endforeach
                                        </td>
                                        <td class="text-right">
                                            <a href="{{url('/promo/'.$promo->id.'/edit')}}" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Update record">
                                                <i class="glyphicon glyphicon-edit"></i>
                                            </a>
                                            <button onclick="deactivateShow({{$promo->id}})" type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deactivate record">
                                                <i class="glyphicon glyphicon-trash"></i>
                                            </button>
                                            {!! Form::open(['method'=>'delete','action' => ['PromoController@destroy',$promo->id],'id'=>'del'.$promo->id]) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="inactiveTable">
                        <table id="dlist" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>Promo</th>
                                    <th class="text-right">Price (PhP)</th>
                                    <th>Products</th>
                                    <th>Services</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deactivate as $promo)
                                    <tr>
                                        <td>{{$promo->name}}</td>
                                        <td class="text-right">{{number_format($promo->price,2)}}</td>
                                        <td>
                                            @foreach($promo->product as $product)
                                                <li>{{$product->product->brand->name}} - {{$product->product->name}} ({{$product->product->variance->name}}) x {{$product->quantity}} pcs.</li>
                                            @endforeach
                                            @if($promo->freeProduct->isNotEmpty())
                                            <b>Free:</b>
                                            @endif
                                            @foreach($promo->freeProduct as $product)
                                                <li>{{$product->product->brand->name}} - {{$product->product->name}} ({{$product->product->variance->name}}) x {{$product->quantity}} pcs.</li>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($promo->service as $service)
                                                <li>{{$service->service->name}} - {{$service->service->size}}</li>
                                            @endforeach
                                            @if($promo->freeService->isNotEmpty())
                                            <b>Free:</b>
                                            @endif
                                            @foreach($promo->freeService as $service)
                                                <li>{{$service->service->name}} - {{$service->service->size}}</li>
                                            @endforeach
                                        </td>
                                        <td class="text-right">
                                            <button onclick="reactivateShow({{$promo->id}})"type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Reactivate record">
                                                <i class="glyphicon glyphicon-refresh"></i>
                                            </button>
                                            {!! Form::open(['method'=>'patch','action' => ['PromoController@reactivate',$promo->id],'id'=>'reactivate'.$promo->id]) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-group pull-right">
                    <label class="checkbox-inline"><input type="checkbox" id="showDeactivated"> Show deactivated records</label>
                </div>
                @include('layouts.reactivateModal')
                @include('layouts.deactivateModal')
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{ URL::asset('assets/datatables/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ URL::asset('js/record.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#list').DataTable({
                responsive: true,
            });
            $('#dlist').DataTable({
                responsive: true,
            });
            $('#maintenance').addClass('active');
            $('#mPromo').addClass('active');
        });
    </script>
@stop
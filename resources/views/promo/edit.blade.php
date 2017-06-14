@extends('layouts.master')

@section('title')
    {{"Promo"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/pace/pace.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/daterangepicker/daterangepicker-bs3.css') }}">
@stop

@section('content')
    {!! Form::model($promo , ['method' => 'patch', 'action' => ['PromoController@update',$promo->id]]) !!}
    @include('promo.form')
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{ URL::asset('assets/datatables/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.numeric.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('js/promo.js') }}"></script>
    <script>
        $(document).ajaxStart(function() { Pace.restart(); });
        $(document).ready(function (){
            $('#maintenance').addClass('active');
            $('#mPromo').addClass('active');
        });
    </script>
    @if($promo->product || $promo->service || $promo->freeProduct || $promo->freeService)
        <script>$('#compute').val(0)</script>
        @if($promo->product)
            @foreach($promo->product as $key=>$product)
                <script>retrieveProduct({{$product->productId}},{{$product->quantity}})</script>
            @endforeach
        @endif
        @if($promo->freeProduct)
            @foreach($promo->freeProduct as $key=>$product)
                <script>retrieveFreeProduct({{$product->productId}},{{$product->quantity}})</script>
            @endforeach
        @endif
        @if($promo->service)
            @foreach($promo->service as $service)
                <script>retrieveService({{$service->serviceId}})</script>
            @endforeach
        @endif
        @if($promo->freeService)
            @foreach($promo->freeService as $service)
                <script>retrieveFreeService({{$service->serviceId}})</script>
            @endforeach
        @endif
    @endif
@stop


@extends('layouts.master')

@section('title')
    {{"Package"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/pace/pace.min.css') }}">
@stop

@section('content')
    {!! Form::model($package , ['method' => 'patch', 'action' => ['PackageController@update',$package->id]]) !!}
    @include('layouts.required')
    @include('package.form')
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
    <script src="{{ URL::asset('js/package.js') }}"></script>
    <script>
        $(document).ajaxStart(function() { Pace.restart(); });
        $(document).ready(function (){
            $('#maintenance').addClass('active');
            $('#mPackage').addClass('active');
        });
    </script>
    @if($package->product || $package->service)
        <script>$('#compute').val(0)</script>
        @if($package->product)
            @foreach($package->product as $key=>$product)
                <script>retrieveProduct({{$product->productId}},{{$product->quantity}})</script>
            @endforeach
        @endif
        @if($package->service)
            @foreach($package->service as $service)
                <script>retrieveService({{$service->serviceId}})</script>
            @endforeach
        @endif
    @endif
@stop


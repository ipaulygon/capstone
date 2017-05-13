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
    {!! Form::open(['url' => 'package']) !!}
    @include('package.form')
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{ URL::asset('assets/datatables/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ URL::asset('js/package.js') }}"></script>
    <script>
        $(document).ajaxStart(function() { Pace.restart(); });
        $(document).ready(function (){
            $('#mPackage').addClass('active');
        });
    </script>
    @if(old('product') || old('service'))
        <script>$('#compute').val(0)</script>
        @if(old('product'))
            @foreach(old('product') as $key=>$product)
                <script>retrieveProduct({{$product}},{{old("qty.".$key)}})</script>
            @endforeach
        @endif
        @if(old('service'))
            @foreach(old('service') as $service)
                <script>retrieveService({{$service}})</script>
            @endforeach
        @endif
    @endif
@stop


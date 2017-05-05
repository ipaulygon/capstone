@extends('layouts.master')

@section('title')
    {{"Product"}}
@stop

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/pace/pace.min.css') }}">
@stop

@section('content')
    {!! Form::model($product,['method' => 'patch', 'action' => ['ProductController@update',$product->id]]) !!}
    @include('product.form')
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{ URL::asset('js/product.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/pace/pace.min.js') }}"></script>
    <script>
        $(document).ajaxStart(function() { Pace.restart(); });
        $(".select2").select2();
        @if($product)
            changeType({{$product->typeId}});
            $('#pt').val({{$product->typeId}}).trigger("change");
        @endif
        $(document).ajaxStop(function() {
            @if($product)
                $('#pb').val({{$product->brandId}}).trigger("change");
                $('#pv').val({{$product->varianceId}}).trigger("change");
            @endif
        });
    </script>
    <script>
        $(document).ready(function (){
            $('#mi').attr('class','treeview active');
            $('#mProduct').attr('class','active');
        });
    </script>
@stop
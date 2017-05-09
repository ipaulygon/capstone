@extends('layouts.master')

@section('title')
    {{"Product"}}
@stop

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/pace/pace.min.css') }}">
@stop

@section('content')
    {!! Form::open(['url' => 'product']) !!}
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
        @if(old('typeId'))
            changeType({{old('typeId')}});
            $('#pt').val({{old('typeId')}}).trigger("change");
        @endif
        $(document).ajaxStop(function() {
            @if(old('typeId'))
                $('#pb').val({{old('brandId')}}).trigger("change");
                $('#pv').val({{old('varianceId')}}).trigger("change");
            @endif
        });
    </script>
    <script>
        $(document).ready(function (){
            $('#mi').addClass('active');
            $('#mProduct').addClass('active');
        });
    </script>
@stop
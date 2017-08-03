@extends('layouts.master')

@section('title')
    {{"Product Brand"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
@stop

@section('content')
    {!! Form::model($brand , ['method' => 'patch', 'action' => ['ProductBrandController@update',$brand->id]]) !!}
    @include('layouts.required')
    @include('brand.form')
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script>
        var activeTypes = [
            @foreach($brand->tb as $tb)
                "{{$tb->type->id}}",
            @endforeach
        ];
        $("#pt").val(activeTypes);
        $(".select2").select2();
    </script>
    <script>
        $(document).ready(function (){
            $('#maintenance').addClass('active');
            $('#mi').addClass('active');
            $('#mBrand').addClass('active');
        });
    </script>
@stop
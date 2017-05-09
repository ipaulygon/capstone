@extends('layouts.master')

@section('title')
    {{"Service"}}
@stop

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
@stop

@section('content')
    {!! Form::model($service , ['method' => 'patch', 'action' => ['ServiceController@update',$service->id]]) !!}
    @include('service.form')
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script>
        $("#sc").val({{$service->categoryId}});
        $("#sizeId[value={{$service->size}}]").prop('checked',true);
        $(".select2").select2();
    </script>
    <script>
        $(document).ready(function (){
            $('#ms').addClass('active');
            $('#mService').addClass('active');
        });
    </script>
@stop
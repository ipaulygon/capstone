@extends('layouts.master')

@section('title')
    {{"Service"}}
@stop

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
@stop

@section('content')
    {!! Form::open(['url' => 'service']) !!}
    @include('service.form')
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script>
        $("#sc").val({{old('categoryId')}});
        $("#sizeId[value={{old('size')}}]").prop('checked',true);
        $(".select2").select2();
    </script>
    <script>
        $(document).ready(function (){
            $('#ms').attr('class','treeview active');
            $('#mService').attr('class','active');
        });
    </script>
@stop
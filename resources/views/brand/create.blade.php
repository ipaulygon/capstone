@extends('layouts.master')

@section('title')
    {{"Brand"}}
@stop

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
@stop

@section('content')
    {!! Form::open(['url' => 'brand']) !!}
    @include('brand.form')
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script>
        var activeTypes = [
            @if(old('type'))
                @foreach(old('type') as $type)
                    "{{$type}}",
                @endforeach
            @endif
        ];
        $("#pt").val(activeTypes);
        $(".select2").select2();
    </script>
    <script>
        $(document).ready(function (){
            $('#mi').attr('class','treeview active');
            $('#mBrand').attr('class','active');
        });
    </script>
@stop
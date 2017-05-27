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
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.numeric.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/pace/pace.min.js') }}"></script>
    <script>
        $(document).ajaxStart(function() { Pace.restart(); });
        var activeVehicles = [
            @if(old('vehicle'))
                @foreach(old('vehicle') as $vehicle)
                    "{{$vehicle}}",
                @endforeach
            @endif
        ];
        $("#vehicle").val(activeVehicles);
        $(".select2").select2();
        @if(old('typeId'))
            changeType({{old('typeId')}});
            $('#pt').val({{old('typeId')}}).trigger("change");
        @endif
        @if(old('isOriginal'))
            $("#isOriginal[value={{old('isOriginal')}}]").prop('checked',true);
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
            $('#maintenance').addClass('active');
            $('#mi').addClass('active');
            $('#mProduct').addClass('active');
            $("#price").inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
                max: 500000,
            });
            $("#reorder").inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: 0,
                max: 100,
            });
        });
    </script>
@stop
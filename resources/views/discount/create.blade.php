@extends('layouts.master')

@section('title')
    {{"Discount"}}
@stop

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
@stop

@section('content')
    {!! Form::open(['url' => 'discount']) !!}
    @include('discount.form')
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.numeric.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script>
        var activeProduct = [
            @if(old('product'))
                @foreach(old('product') as $product)
                    "{{$product}}",
                @endforeach
            @endif
        ];
        var activeService = [
            @if(old('service'))
                @foreach(old('service') as $service)
                    "{{$service}}",
                @endforeach
            @endif
        ];
        $("#product").val(activeProduct);
        $("#service").val(activeService);
        $(".select2").select2();
    </script>
    <script>
        $(document).ready(function (){
            $('#mDiscount').addClass('active');
            $("#rate").inputmask({ 
                alias: "percentage",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
                max: 100,
            });
        });
    </script>
@stop
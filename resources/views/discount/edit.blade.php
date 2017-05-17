@extends('layouts.master')

@section('title')
    {{"Discount"}}
@stop

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
@stop

@section('content')
    {!! Form::model($discount , ['method' => 'patch', 'action' => ['DiscountController@update',$discount->id]]) !!}
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
            @foreach($discount->product as $product)
                "{{$product->product->id}}",
            @endforeach
        ];
        var activeService = [
            @foreach($discount->service as $service)
                "{{$service->service->id}}",
            @endforeach
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
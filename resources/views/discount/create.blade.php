@extends('layouts.master')

@section('title')
    {{"Discount"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
@stop

@section('content')
    {!! Form::open(['url' => 'discount']) !!}
    @include('layouts.required')
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
        @if(old('isWhole'))
            $(".square-blue[value={{old('isWhole')}}]").prop('checked',true);
        @else
            $(".square-blue[value=0]").prop('checked',true);
        @endif
        $("#product").val(activeProduct);
        $("#service").val(activeService);
        $(".select2").select2();
        $('#type1').on('ifChecked ifUnchecked', function(event){
            if(event.type=="ifChecked"){
                $('#product').val('');
                $('#service').val('');
                $('.select2').select2();
                $(".select2").prop('disabled',true);
                $(".vat").prop('disabled',false);
                $(".vat").prop('checked',false);
                $("#isVatExempt").val(0);
            }else{
                $(".select2").prop('disabled',false);
                $(".vat").prop('disabled',true);
                $(".vat").prop('checked',false);
                $("#isVatExempt").val(0);
            }
        });
        $('.vat').change(function(){
            if($(this).prop('checked')){
                $('#isVatExempt').val(1);
            }else{
                $('#isVatExempt').val(0);
            }
        });
    </script>
    <script>
        $(document).ready(function (){
            $('#maintenance').addClass('active');
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
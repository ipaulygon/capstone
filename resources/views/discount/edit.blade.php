@extends('layouts.master')

@section('title')
    {{"Discount"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
@stop

@section('content')
    {!! Form::model($discount , ['method' => 'patch', 'action' => ['DiscountController@update',$discount->id]]) !!}
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
        $(".type[value={{$discount->isWhole}}]").prop('checked',true);
        @if($discount->isWhole)
            if(isVat){
                $('#vatForm').removeClass('hidden');
                $(".vat").prop('disabled',false);
                @if($discount->isVatExempt)
                    $(".vat").prop('checked',true);
                    $("#isVatExempt").val(1);
                @endif
            }
            $('#product').val('');
            $('#service').val('');
            $('.select2').select2();
            $(".select2").prop('disabled',true);
        @endif
        $('#type1').on('ifChecked ifUnchecked', function(event){
            if(event.type=="ifChecked"){
                $('#product').val('');
                $('#service').val('');
                $('.select2').select2();
                $(".select2").prop('disabled',true);
                if(isVat){
                    $('#vatForm').removeClass('hidden');
                    $(".vat").prop('disabled',false);
                    $(".vat").prop('checked',false);
                    $("#isVatExempt").val(0);
                }
            }else{
                $(".select2").prop('disabled',false);
                if(isVat){
                    $('#vatForm').addClass('hidden');
                    $(".vat").prop('disabled',true);
                    $(".vat").prop('checked',false);
                    $("#isVatExempt").val(0);
                }
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
                min: 1,
                max: 100,
            });
        });
    </script>
@stop
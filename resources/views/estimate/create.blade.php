@extends('layouts.master')

@section('title')
    {{"Estimate Repair"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/pace/pace.min.css') }}">
@stop

@section('content')
    <div class="col-md-12">
        {!! Form::open(['url' => 'estimate']) !!}
        @include('estimate.formCreate')
        {!! Form::close() !!}
    </div>
@stop

@section('script')
    <script src="{{ URL::asset('assets/plugins/sketch/sketch.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.numeric.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.phone.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ URL::asset('js/estimate.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#simple_sketch').sketch();
            var customers = [
                @foreach($customers as $customer)
                    '{{$customer->firstName}} {{$customer->middleName}} {{$customer->lastName}}',
                @endforeach
            ];    
            var activeTechnicians = [
                @if(old('technician'))
                    @foreach(old('technician') as $technician)
                        "{{$technician}}",
                    @endforeach
                @endif
            ];
            $("#technician").val(activeTechnicians);
            @if(old('modelId'))
                $("#model").val({{old('modelId')}});
            @endif
            $(".select2").select2();
            $('#firstName').autocomplete({source: customers});
            $('#contact').inputmask("(+639)99-9999-999");
            $('#plate').inputmask("AAA 9999");
            $('#email').inputmask("email");
            $("#mileage").inputmask({ 
                alias: "decimal",
                prefix: '',
                suffix: ' km',
                allowMinus: false,
                min: 0,
            });
            $("#compute").inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
            });
            $('#tEstimate').addClass('active');
        });
        $(document).on('keypress','#contact',function(){
            if($(this).val()[4]=='9'){
                $(this).inputmask("(+639)99-9999-999");
            }else if($(this).val()[4]=='2'){
                $(this).inputmask("(+639)999-9999");
            }else{
                $(this).inputmask("(+639) ERROR");
            }
        });
    </script>
    @if(old('product') || old('service') || old('package') || old('promo'))
        <script>$('#compute').val(0)</script>
        @if(old('product'))
        @foreach(old('product') as $key=>$product)
            <script>retrieveProduct({{$product}},{{old("productQty.".$key)}})</script>
        @endforeach
        @endif
        @if(old('service'))
        @foreach(old('service') as $key=>$service)
            <script>retrieveService({{$service}})</script>
        @endforeach
        @endif
        @if(old('package'))
        @foreach(old('package') as $key=>$package)
            <script>retrievePackage({{$package}},{{old("packageQty.".$key)}})</script>
        @endforeach
        @endif
        @if(old('promo'))
        @foreach(old('promo') as $key=>$promo)
            <script>retrievePromo({{$promo}},{{old("promoQty.".$key)}})</script>
        @endforeach
        @endif
    @endif
@stop
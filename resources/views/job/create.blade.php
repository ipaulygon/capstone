@extends('layouts.master')

@section('title')
    {{"Job Repair"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/pace/pace.min.css') }}">
@stop

@section('content')
    <div class="col-md-12">
        {!! Form::open(['url' => 'job']) !!}
        @include('job.formCreate')
        {!! Form::close() !!}
    </div>
@stop

@section('script')
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
    <script src="{{ URL::asset('js/job.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#tJob').addClass('active');
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
            @if(!old('contact'))
                $('#contact').inputmask("+63 999 9999 999");
            @else
                @if(old('contact')[2] == '2' && old('contact')[14] == 'l')
                    $('#contact').inputmask("(02) 999 9999 loc. 9999");
                @elseif(old('contact')[2] == '2')
                    $('#contact').inputmask("(02) 999 9999");
                @else
                    $('#contact').inputmask("+63 999 9999 999");
                @endif
            @endif
            @if(!old('plate'))
                $('#plate').inputmask("AAA 999");
            @else
                @if(strlen(old('plate')) == 7)
                    $('#plate').inputmask("AAA 999");
                @elseif(strlen(old('plate')) == 8)
                    $('#plate').inputmask("AAA 9999");
                @else
                    $('#plate').inputmask();
                    $('#plate').val("For Registration");
                @endif
            @endif
        });
    </script>
    @if(old('product') || old('service') || old('package') || old('promo') || old('discount'))
        <script>$('#compute').val(0)</script>
        @if(old('product'))
        @foreach(old('product') as $key=>$product)
            <script>oldProduct({{$product}},{{old("productQty.".$key)}})</script>
        @endforeach
        @endif
        @if(old('service'))
        @foreach(old('service') as $key=>$service)
            <script>oldService({{$service}})</script>
        @endforeach
        @endif
        @if(old('package'))
        @foreach(old('package') as $key=>$package)
            <script>oldPackage({{$package}},{{old("packageQty.".$key)}})</script>
        @endforeach
        @endif
        @if(old('promo'))
        @foreach(old('promo') as $key=>$promo)
            <script>oldPromo({{$promo}},{{old("promoQty.".$key)}})</script>
        @endforeach
        @endif
        @if(old('discount'))
        @foreach(old('discount') as $key=>$discount)
            <script>oldDiscount({{$discount}})</script>
        @endforeach
        @endif
    @endif
@stop
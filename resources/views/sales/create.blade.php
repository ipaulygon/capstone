@extends('layouts.master')

@section('title')
    {{"Point of Sales"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/pace/pace.min.css') }}">
@stop

@section('content')
    {!! Form::open(['url' => 'sales']) !!}
    <div class="col-md-12">
        <a href="{{ URL::to('/sales') }}" class="btn btn-success btn-md">
        <i class="glyphicon glyphicon-eye-open"></i> View Transactions</a>
    </div><br><br>
    @include('layouts.required')
    @include('sales.formCreate')
    {!! Form::close() !!}
    @include('layouts.inventoryList')
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
    <script src="{{ URL::asset('js/customer.js') }}"></script>
    <script src="{{ URL::asset('js/inventoryList.js') }}"></script>
    <script src="{{ URL::asset('js/sales.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#tSales').addClass('active');
            var customers = [
                @foreach($customers as $customer)
                    '{{$customer->firstName}} {{$customer->middleName}} {{$customer->lastName}}',
                @endforeach
            ];    
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
        });
    </script>
    @include('layouts.oldSales')
    <script>
        if(isVat){
            $("#vatRate").inputmask({ 
                alias: "percentage",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
                max: 100,
            });
            $('#vatStack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: true,
                min: 0,
            });
            $('#vatSales').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: true,
                min: 0,
            });
            $('#vatExempt').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: true,
            });
        }
    </script>
@stop
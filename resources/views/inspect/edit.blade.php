@extends('layouts.master')

@section('title')
    {{"Inspect Vehicle"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/formbuilder/form-builder.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/formbuilder/form-render.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/pace/pace.min.css') }}">
@stop

@section('content')
    {!! Form::model($inspect , ['method' => 'patch', 'action' => ['InspectController@update',$inspect->id]]) !!}
    @include('inspect.formEdit')
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{ URL::asset('assets/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/formbuilder/form-builder.min.js') }}"></script>
    <script src="{{ URL::asset('assets/formbuilder/form-render.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.numeric.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.phone.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ URL::asset('js/customer.js') }}"></script>
    <script src="{{ URL::asset('js/inspect.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#tInspect').addClass('active');
            var customers = [
                @foreach($customers as $customer)
                    '{{$customer->firstName}} {{$customer->middleName}} {{$customer->lastName}}',
                @endforeach
            ];
            var activeTechnicians = [
                @foreach($inspect->technician as $technician)
                    "{{$technician->technicianId}}",
                @endforeach
            ];
            $("#technician").val(activeTechnicians);
            $("#model").val({{$inspect->vehicle->modelId}});
            $(".select2").select2();
            $('#firstName').autocomplete({source: customers});
            @if($inspect->customer->contact[2] == '2' && old('contact')[14] == 'l')
                $('#contact').inputmask("(02) 999 9999 loc. 9999");
            @elseif($inspect->customer->contact == '2')
                $('#contact').inputmask("(02) 999 9999");
            @else
                $('#contact').inputmask("+63 999 9999 999");
            @endif
            @if(strlen($inspect->vehicle->plate) == 7)
                $('#plate').inputmask("AAA 999");
            @elseif(strlen($inspect->vehicle->plate)== 8)
                $('#plate').inputmask("AAA 9999");
            @elseif(strlen($inspect->vehicle->plate) == 6)
                @if($inspect->vehicle->plate[3] != ' ')
                    $('#plate').inputmask("AA 9999");
                @else
                    $('#plate').inputmask("AAA 99");
                @endif
            @elseif(strlen($inspect->vehicle->plate) == 1)
                $('#plate').inputmask("9");
            @else
                $('#plate').inputmask();
                $('#plate').val("For Registration");
            @endif
        });
    </script>
    @foreach($inspect->detail as $detail)
        <script>
            form = JSON.stringify({!! $detail->remarks !!});
            popForm({{$detail->item->typeId}},"{{$detail->item->type->type}}",{{$detail->itemId}},"{{$detail->item->name}}",form)
        </script>
    @endforeach
@stop
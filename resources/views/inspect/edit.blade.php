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
    <div class="col-md-12">
        {!! Form::model($inspect , ['method' => 'patch', 'action' => ['InspectController@update',$inspect->id]]) !!}
        @include('inspect.formEdit')
        {!! Form::close() !!}
    </div>
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
    <script src="{{ URL::asset('js/inspect.js') }}"></script>
    <script>
        $(document).ready(function (){
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
            $('#contact').inputmask("(+639)99-9999-999");
            $('#plate').inputmask("AAA 9999");
            $('#email').inputmask("email");
            $("#mileage").inputmask({ 
                alias: "decimal",
                prefix: '',
                allowMinus: false,
                min: 0,
            });
            $('#tInspect').addClass('active');
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
    @foreach($inspect->detail as $detail)
        <script>
            form = JSON.stringify({!! $detail->remarks !!});
            popForm({{$detail->item->typeId}},"{{$detail->item->type->type}}",{{$detail->itemId}},"{{$detail->item->name}}",form)
        </script>
    @endforeach
@stop
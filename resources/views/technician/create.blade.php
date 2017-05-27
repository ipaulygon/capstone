@extends('layouts.master')

@section('title')
    {{"Technician"}}
@stop

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/datepicker/bootstrap-datepicker.css') }}">
@stop

@section('content')
    {!! Form::open(['url' => 'technician','files' => true]) !!}
    @include('technician.form')
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{ URL::asset('assets/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.phone.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script>
        $(document).ready(function (){
            $('#maintenance').addClass('active');
            $('#ms').addClass('active');
            $('#mTechnician').addClass('active');
            $('#contact').inputmask("(+639)99-9999-999");
            $('#email').inputmask("email");
            $('#bday').datepicker({
                format: 'mm/dd/yyyy',
                endDate: new Date,
                autoclose: false,
                todayHighlight: true,
            });
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
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#tech-pic')
                        .attr('src', e.target.result)
                        .width(180);
                    };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@stop
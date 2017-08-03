@extends('layouts.master')

@section('title')
    {{"Technician"}}
@stop

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/datepicker/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
@stop

@section('content')
    {!! Form::model($technician , ['method' => 'patch', 'action' => ['TechnicianController@update',$technician->id],'files' => true]) !!}
    @include('layouts.required')
    @include('technician.form')
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{ URL::asset('assets/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.phone.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('js/technician.js') }}"></script>
    <script src="{{ URL::asset('js/birthday.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#maintenance').addClass('active');
            $('#ms').addClass('active');
            $('#mTechnician').addClass('active');
            getAge();
            var activeSkill = [
                @foreach($technician->skill as $skill)
                    "{{$skill->categoryId}}",
                @endforeach
            ];
            $('#ts').val(activeSkill);
            $(".select2").select2();
            @if($technician->contact[2] == '2')
                $('#contact').inputmask("(02) 999 9999");
            @else
                $('#contact').inputmask("+63 999 9999 999");
            @endif
        });
    </script>
@stop
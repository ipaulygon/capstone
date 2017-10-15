@extends('layouts.master')

@section('title')
    {{"Backup & Recovery"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/pace/pace.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
@stop

@section('content')
    {!! Form::open(['url' => 'backup']) !!}
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Backup Configuration</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group">
                    {!! Form::label('type', 'Backup Type') !!}<span>*</span>
                    <div class="row">
                        <div class="col-md-6">
                            <input id="conf" type="radio" class="square-blue" name="config" value="manual" required> Manual
                        </div>
                        <div class="col-md-6">
                            <input id="conf" type="radio" class="square-blue" name="config" value="auto" required checked> Automatic
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Recovery Actions</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group">
                    {!! Form::label('dump', 'Save database') !!}<br>
                    <a href="{{url('/backup/dump')}}" target="_blank" type="button" class="btn btn-md btn-primary">Dump</a>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('sql', 'Upload SQL file') !!}<span>*</span>
                        {!! Form::file('sql',[
                            'class' => 'form-control',
                            'name' => 'sql',
                            'class' => 'btn btn-success btn-sm']) 
                        !!}
                    </div>
                </div>
            </div>
            <div class="box-footer">
                {!! Form::submit('Upload SQL', ['class'=>'btn btn-primary']) !!}
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#backup').addClass('active');
            $("#conf[value=auto]").prop('checked',true);
            $(".select2").select2();
        });
    </script>
@stop
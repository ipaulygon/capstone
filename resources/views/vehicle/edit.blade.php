@extends('layouts.master')

@section('title')
    {{"Vehicle Type"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/pace/pace.min.css') }}">
@stop

@section('content')
    {!! Form::model($make , ['method' => 'patch', 'action' => ['VehicleController@update',$make->id]]) !!}
    @include('layouts.required')
    @include('vehicle.form')
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Model(s)</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div id="models" class="box-body">
                @if(count($make->model)>0)
                    @foreach($make->model as $model)
                        <div id="model" class="form-group">
                            @if($loop->index!=0)
                                <button id="removeModel" type="button" class="btn btn-flat btn-danger btn-xs pull-right">
                                    <i class="glyphicon glyphicon-remove"></i>
                                </button><br>
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="hidden" class="hidden" value="{{$model->id}}">
                                    {!! Form::label('model', 'Model') !!}<span>*</span>
                                    {!! Form::input('text',null,$model->name,[
                                        'class' => 'form-control model',
                                        'name' => 'model[]',
                                        'placeholder' => 'Model',
                                        'maxlength' => '50',
                                        'required'])
                                    !!}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('transmission', 'Transmission') !!}<span>*</span>
                                        <div class="row transmission">
                                            <div class="col-md-6">
                                                <label class="checkbox-inline">
                                                    @if($model->hasAuto)
                                                        <input type="checkbox" class="check auto" name="auto[]" value="1" checked> Automatic
                                                        <input type="hidden" name="hasAuto[]" value="1">
                                                    @else
                                                        <input type="checkbox" class="check auto" name="auto[]" value="1"> Automatic
                                                        <input type="hidden" name="hasAuto[]" value="0">
                                                    @endif
                                                </label>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="checkbox-inline">
                                                    @if($model->hasManual)
                                                        <input type="checkbox" class="check manual" name="manual[]" value="1" checked> Manual
                                                        <input type="hidden" name="hasManual[]" value="1">
                                                    @else
                                                        <input type="checkbox" class="check manual" name="manual[]" value="1"> Manual
                                                        <input type="hidden" name="hasManual[]" value="0">
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div id="model" class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('model', 'Model') !!}<span>*</span>
                                {!! Form::input('text',null,null,[
                                    'class' => 'form-control model',
                                    'name' => 'model[]',
                                    'placeholder' => 'Name',
                                    'maxlength' => '50',
                                    'required'])
                                !!}
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('transmission', 'Transmission') !!}<span>*</span>
                                    <div class="row transmission">
                                        <div class="col-md-6">
                                            <label class="checkbox-inline">
                                                <input type="checkbox" class="check auto" name="auto[]" value="1" checked> Automatic
                                                <input type="hidden" name="hasAuto[]" value="1">
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="checkbox-inline">
                                                <input type="checkbox" class="check manual" name="manual[]" value="1"> Manual
                                                <input type="hidden" name="hasManual[]" value="0">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="box-footer">
                <button id="addModel" type="button" class="btn btn-sm btn-primary pull-right">
                    <i class="glyphicon glyphicon-plus"></i>
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{ URL::asset('assets/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.numeric.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ URL::asset('js/vehicle.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#maintenance').addClass('active');
            $('#mVehicle').addClass('active');
        });
    </script>
@stop
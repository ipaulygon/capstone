@extends('layouts.master')

@section('title')
    {{"Vehicle"}}
@stop

@section('content')
    {!! Form::model($make , ['method' => 'patch', 'action' => ['VehicleController@update',$make->id]]) !!}
    <div class="col-md-4">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Vehicle Information</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    {!! Form::label('name', 'Vehicle Make') !!}<span>*</span>
                    {!! Form::input('text','name',null,[
                        'class' => 'form-control',
                        'placeholder'=>'Name',
                        'maxlength'=>'50',
                        'required']) 
                    !!}
                </div>
            </div>
            <div class="box-footer">
                {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
            </div>
        </div>
    </div>
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
                @foreach($make->model as $model)
                    <div id="model" class="form-group">
                        @if($loop->index!=0)
                            <button id="removeModel" type="button" class="btn btn-flat btn-danger btn-xs pull-right">
                                <i class="glyphicon glyphicon-remove"></i>
                            </button><br>
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('model', 'Model') !!}<span>*</span>
                                {!! Form::input('text',null,$model->name,[
                                    'class' => 'form-control',
                                    'name' => 'model[]',
                                    'placeholder' => 'Model',
                                    'maxlength' => '50',
                                    'required'])
                                !!}
                            </div>
                            <div class="col-md-2">
                                {!! Form::label('year', 'Year') !!}<span>*</span>
                                {!! Form::input('text',null,$model->year,[
                                    'class' => 'form-control year',
                                    'name' => 'year[]',
                                    'placeholder' => 'Year',
                                    'required'])
                                !!}
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('trasmission', 'Transmission') !!}<span>*</span>
                                    <select id="transmission" name="transmission[]" class="form-control" required>
                                        @if($model->transmission=='AT')
                                            <option value="AT" selected>Automatic</option>
                                            <option value="MT">Manual</option>
                                        @else
                                            <option value="AT">Automatic</option>
                                            <option value="MT" selected>Manual</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.numeric.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ URL::asset('js/vehicle.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#maintenance').addClass('active');
            $('#mVehicle').addClass('active');
            $('.year').inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: 1900,
                max: (new Date()).getFullYear(),
            });
        });
    </script>
@stop
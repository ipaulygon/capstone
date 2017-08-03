@extends('layouts.master')

@section('title')
    {{"Product Variance"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/pace/pace.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
@stop

@section('content')
    {!! Form::model($variance , ['method' => 'patch', 'action' => ['ProductVarianceController@update',$variance->id]]) !!}
    @include('layouts.required')
    @include('variance.form')
    <div class="col-md-4">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Dimension(s)</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div id="dimensions" class="box-body">
                {{-- For retrieving input --}}
                @foreach($activeSize as $key => $dimension)
                    <div id="dimension" class="form-group">
                        @if($loop->index!=0)
                            <button id="removeDimension" type="button" class="btn btn-flat btn-danger btn-xs pull-right">
                                <i class="glyphicon glyphicon-remove"></i>
                            </button>
                        @endif
                        {!! Form::label('dimension', 'Dimension') !!}<span>*</span>
                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::input('text',null,$dimension,[
                                    'class' => 'form-control dim',
                                    'name' => 'dimension[]',
                                    'placeholder'=>'Dimension',
                                    'maxlength'=>'50',
                                    'required'])
                                !!}
                            </div>
                            <div class="col-md-4">
                                <select id="unit" name="unit[]" class="form-control" required>
                                    @foreach($units as $unit)
                                        @if($unit->id == $activeUnit[$key])
                                            <option value="{{$unit->id}}" selected>{{$unit->name}}</option>
                                        @else
                                            <option value="{{$unit->id}}">{{$unit->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="box-footer">
                <button id="addDimension" type="button" class="btn btn-sm btn-primary pull-right">
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
    <script src="{{ URL::asset('assets/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('js/variance.js') }}"></script>
    <script>
        var activeTypes = [
            @foreach($variance->tv as $tv)
                "{{$tv->typeId}}",
            @endforeach
        ];
        $("#pt").val(activeTypes);
        $('#uc').val({{$category}});
        $(".select2").select2();
    </script>
    <script>
        $(document).ready(function (){
            $('#maintenance').addClass('active');
            $('#mi').addClass('active');
            $('#mVariance').addClass('active');
            $(".dim").inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: 0,
                max: 10000,
            });
        });
    </script>
@stop
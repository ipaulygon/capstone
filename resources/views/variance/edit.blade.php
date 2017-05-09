@extends('layouts.master')

@section('title')
    {{"Product Variance"}}
@stop

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
@stop

@section('content')
    {!! Form::model($variance , ['method' => 'patch', 'action' => ['ProductVarianceController@update',$variance->id]]) !!}
    @include('variance.form')
    <div class="col-md-6">
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
                            <div class="col-md-9">
                                {!! Form::input('text',null,$dimension,[
                                    'class' => 'form-control',
                                    'name' => 'dimension[]',
                                    'placeholder'=>'Dimension',
                                    'maxlength'=>'50',
                                    'required'])
                                !!}
                            </div>
                            <div class="col-md-3">
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
                    <script>
                        $('#unit').last().val("{{$activeUnit[$key]}}");
                    </script>
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
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('js/variance.js') }}"></script>
    <script>
        var activeTypes = [
            @foreach($variance->tv as $tv)
                "{{$tv->typeId}}",
            @endforeach
        ];
        $("#pt").val(activeTypes);
        $("#isOriginal[value={{$variance->isOriginal}}]").prop('checked',true);
        $(".select2").select2();
    </script>
    <script>
        $(document).ready(function (){
            $('#mi').addClass('active');
            $('#mVariance').addClass('active');
        });
    </script>
@stop
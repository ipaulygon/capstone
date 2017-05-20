@extends('layouts.master')

@section('title')
    {{"Dashboard"}}
@stop

@section('content')
    <div class="col-md-12">
        {{-- Jobs --}}
        <div class="col-md-7">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Jobs</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">

                </div>
            </div>
        </div>
        {{-- end of component --}}
        {{-- Reorder --}}
        <div class="col-md-5">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Reorder</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">

                </div>
            </div>
        </div>
        {{-- end of component --}}
    </div>
@stop

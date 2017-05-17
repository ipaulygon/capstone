@extends('layouts.master')

@section('title')
    {{"Maintenance Failure"}}
@stop

@section('content')
    <div class="col-md-12">
        <section class="content">
            <div class="error-page">
                <h2 class="headline text-yellow"> Error!</h2>

                <div class="error-content">
                <h3><i class="fa fa-warning text-yellow"></i> Oops! Maintenance Failure.</h3>

                <h3>
                    We could not find any resources that is needed in this maintenance feature.
                    You might want to click <a href="{{url($link)}}">this</a> link.
                </h3>
                </div>
                <!-- /.error-content -->
            </div>
            <!-- /.error-page -->
        </section>
    </div>
@stop
@extends('layouts.master')

@section('title')
    {{"404 Not Found"}}
@stop

@section('content')
    <div class="col-md-12">
        <section class="content">
            <div class="error-page">
                <h2 class="headline text-yellow"> 404</h2>

                <div class="error-content">
                <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>

                <h3>
                    We could not find the page you were looking for.
                    Meanwhile, you may return to <a href="{{url('/dashboard')}}">dashboard</a>.
                </h3>
                </div>
                <!-- /.error-content -->
            </div>
            <!-- /.error-page -->
        </section>
    </div>
@stop
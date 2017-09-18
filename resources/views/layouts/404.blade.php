<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{$util->name}} | @yield('title')</title>

    <!-- Styles -->
    {{-- <link rel="stylesheet" href="{{ URL::asset('assets/style.css') }}"> --}}
    @yield('style')
    <link rel="stylesheet" href="{{ URL::asset('assets/jquery/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/bootstrap/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/bootstrap/css/ionicons.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/skins/_all-skins.min.css') }}">
    <style>
        form{
            font-family: "Segoe UI"
        }
        form span{
            font-weight: 580;
            color: red;
        }
        form input{
            font-weight: 580;
        }
        form select{
            font-weight: 580;
        }
        form textarea{
            font-weight: 580;
        }
        label{
            font-weight: 600;
        }
        @media (min-width: 992px){
            .dataTables_filter{
                float:left;
            }
            .dataTables_length{
                float:right;
            }
        }
    </style>
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
        var maxValue = {{$util->max}};
    </script>
</head>
<body id="mainBody" class="fixed hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
            <!-- Logo -->
            <a href="{{url('/dashboard')}}" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b></b></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>{{$util->name}}</b></span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Notifications: style can be found in dropdown.less -->
                    </ul>
                </div>
            </nav>
        </header>
        <div class="content-wrapper" style="min-height: 941px;">
            <!-- Main content -->
            <section class="content">
                <div class="row">
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
                </div>
            </section>
            <!-- /.content -->
        </div>
        {{-- <footer class="main-footer">
            <strong>Copyright © 2017 <a href="http://facebook.com">Rapide</a>.</strong> All rights reserved.
        </footer> --}}
    </div>
    <!-- Scripts -->
    <!-- jQuery 2.2.0 -->
    <script src="{{ URL::asset('assets/jquery/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('assets/jquery/jquery-ui.min.js') }}"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="{{ URL::asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- SlimScroll -->
    <script src="{{ URL::asset('assets/plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/iCheck/icheck.min.js')}}"></script>
    <!-- FastClick -->
    <script src="{{ URL::asset('assets/plugins/fastclick/fastclick.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{ URL::asset('assets/dist/js/app.min.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ URL::asset('assets/dist/js/demo.js')}}"></script>
</body>
</html>

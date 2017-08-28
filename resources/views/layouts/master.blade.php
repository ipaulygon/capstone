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
    <link rel="stylesheet" href="{{ URL::asset('css/custom.css') }}">
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
        var type1 = "{{$util->type1}}";
        var type2 = "{{$util->type2}}";
        var maxValue = {{$util->max}};
        var backlog = {{$util->backlog}};
        var isVat = {{$util->isVat}};
        var vatRate = {{$util->vat}};
    </script>
</head>
<body id="mainBody" class="fixed hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
            <!-- Logo -->
            <a href="{{url('/dashboard')}}" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>{{$util->image}}</b></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>{{$util->name}}</b></span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Notifications: style can be found in dropdown.less -->
                        {{--  <li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-success">10</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 10 notifications</li>
                                <li>
                                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;">
                                        <ul class="menu" style="overflow: hidden; width: 100%; height: 200px;">
                                                <li>
                                                    <a href="#">
                                                    <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                                    </a>
                                                </li>
                                            <div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 3px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px;">
                                            </div>
                                            <div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;">
                                            </div>
                                        </ul>
                                    </div>
                                </li>
                                <li class="footer"><a href="#">View all</a></li>
                            </ul>
                        </li>  --}}
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ URL::asset($userPicture)}}" class="user-image" alt="User Image">
                            <span class="hidden-xs">{{$userName}}</span>
                            </a>
                            <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="{{ URL::asset($userPicture)}}" class="img-circle" alt="User Image">
                                <p>{{$wholeName}}</p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-right">
                                <a class="btn btn-default btn-flat" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    Sign out
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                                </div>
                            </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar" style="height: auto;">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="{{ URL::asset($userPicture)}}" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p>{{$wholeName}}</p>
                    </div>
                </div>
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu">
                    <li id="dashboard">
                        <a href="{{url('/dashboard')}}">
                            <i class="fa fa-line-chart"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    @if($user->type==1)
                    <li class="header">MAINTENANCE</li>
                    <li id="maintenance" class="treeview">
                        <a>
                            <i class="fa fa-database"></i>
                            <span>Maintenance</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li id="mVehicle"><a href="{{url('/vehicle')}}"><i class="fa fa-car"></i> <span>Vehicle Type</span></a></li>
                            <li id="mi" class="treeview">
                                <a>
                                    <i class="fa fa-shopping-cart"></i>
                                    <span>Inventory</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li id="mSupplier"><a href="{{url('/supplier')}}"><i class="fa fa-circle-o"></i> Supplier</a></li>
                                    <li id="mType"><a href="{{url('/type')}}"><i class="fa fa-circle-o"></i> Product Type</a></li>
                                    <li id="mBrand"><a href="{{url('/brand')}}"><i class="fa fa-circle-o"></i> Product Brand</a></li>
                                    <li id="mUnit"><a href="{{url('/unit')}}"><i class="fa fa-circle-o"></i> Product Unit</a></li>
                                    <li id="mVariance"><a href="{{url('/variance')}}"><i class="fa fa-circle-o"></i> Product Variance</a></li>
                                    <li id="mProduct"><a href="{{url('/product')}}"><i class="fa fa-circle-o"></i> Product</a></li>
                                </ul>
                            </li>
                            <li id="ms" class="treeview">
                                <a>
                                    <i class="fa fa-wrench"></i>
                                    <span>Service</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li id="mCategory"><a href="{{url('/category')}}"><i class="fa fa-circle-o"></i> Service Category</a></li>
                                    <li id="mService"><a href="{{url('/service')}}"><i class="fa fa-circle-o"></i> Service</a></li>
                                    <li id="mRack"><a href="{{url('/rack')}}"><i class="fa fa-circle-o"></i> Rack</a></li>
                                    <li id="mInspection"><a href="{{url('/inspection')}}"><i class="fa fa-circle-o"></i> Inspection</a></li>
                                    <li id="mTechnician"><a href="{{url('/technician')}}"><i class="fa fa-circle-o"></i>Technician</a></li>
                                </ul>
                            </li>
                            <li id="mPackage"><a href="{{url('/package')}}"><i class="fa fa-gift"></i> <span>Package</span></a></li>
                            <li id="mPromo"><a href="{{url('/promo')}}"><i class="fa fa-bookmark"></i> <span>Promo</span></a></li>
                            <li id="mDiscount"><a href="{{url('/discount')}}"><i class="fa fa-tags"></i> <span>Discounts</span></a></li>
                        </ul>
                    </li>
                    @endif
                    <li class="header">TRANSACTIONS</li>
                    @if($user->type==1)
                    <li id="tPurchase"><a href="{{url('/purchase')}}"><i class="fa fa-clipboard"></i> <span>Purchase Order</span></a></li>
                    <li id="tDelivery"><a href="{{url('/delivery')}}"><i class="fa fa-truck"></i> <span>Receive Delivery</span></a></li>
                    <li id="tReturn"><a href="{{url('/return')}}"><i class="fa fa-share"></i> <span>Return Items</span></a></li>
                    <li id="tSales"><a href="{{url('/sales')}}"><i class="fa fa-money"></i> <span>Point of Sales</span></a></li>
                    @endif
                    <li id="tInspect"><a href="{{url('/inspect')}}"><i class="fa fa-search"></i> <span>Inspect Vehicle</span></a></li>
                    <li id="tJob"><a href="{{url('/job')}}"><i class="fa fa-tasks"></i> <span>Job Order</span></a></li>
                    @if($user->type==1)
                    <li class="header">QUERIES & REPORTS</li>
                    <li id="query"><a href="{{url('/query')}}"><i class="fa fa-bookmark-o"></i> <span>Queries</span></a></li>
                    <li id="report"><a href="{{url('/report')}}"><i class="fa fa-book"></i> <span>Reports</span></a></li>
                    <li class="header">UTILITIES</li>
                    <li id="utility"><a href="{{url('/utility')}}"><i class="fa fa-gears"></i> <span>Utilities</span></a></li>
                    @endif
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>
        <div class="content-wrapper" style="min-height: 941px;">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>@yield('title')</h1>
            </section>

            <!-- Main content -->
            <section class="content">
                @include('layouts.notification')
                <div class="row">
                    @yield('content')
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
    {{--  <script>
        $('#mainBody').addClass('sidebar-collapse');
    </script>    --}}
    <script>
        $(function () {
            //iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
            //Red color scheme for iCheck
            $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
                checkboxClass: 'icheckbox_minimal-red',
                radioClass: 'iradio_minimal-red'
            });
            //Flat blue color scheme for iCheck
            $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'iradio_flat-blue'
            });
            //Square blue color scheme for icheck
            $('input[type="checkbox"].square-blue, input[type="radio"].square-blue').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue'
            });
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        setTimeout(function(){
            $('#successAlert').alert('close');
        },4000)
    </script>
    @yield('script')
</body>
</html>

@extends('layouts.master')

@section('title')
    {{"Job Order"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/pace/pace.min.css') }}">
@stop

@section('content')
    <div id="jobCarousel" class="carousel slide">
        <div class="carousel-inner" role="listbox">
            {{-- INDEX --}}
            <div id="jobIndex" class="item active">
                {{--  DETAIL BOX  --}}
                <div class="col-md-4">                    
                    <div id="actionBox" class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 id="dateSelected" class="box-title">{{$date}}</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <button id="addNew" class="btn btn-success btn-block">
                                <i class="glyphicon glyphicon-plus"></i> New Record
                            </button>
                            <button id="viewMonth" class="btn btn-primary btn-block">
                                <i class="fa fa-calendar"></i> Month View
                            </button>
                            <button id="viewWeek" class="btn btn-warning btn-block">
                                <i class="fa fa-calendar-minus-o"></i> Week View
                            </button>
                            <button id="viewDay" class="btn btn-danger btn-block disabled">
                                <i class="fa fa-calendar-o"></i> Day View
                            </button>
                            <button id="viewTable" class="btn btn-info btn-block" href="#tabularTab" aria-controls="tabularTab" role="tab" data-toggle="tab">
                                <i class="fa fa-exchange"></i> Tabular View
                            </button>
                            <button id="viewCalendar" class="btn btn-info btn-block hidden" href="#calendarTab" aria-controls="calendarTab" role="tab" data-toggle="tab">
                                <i class="fa fa-exchange"></i> Calendar View
                            </button>
                        </div>
                    </div>
                    <div id="detailBox" class="box box-info hidden">
                        <div class="box-header with-border">
                            <h3 class="box-title">Job Order Details</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Job Id:</label> <span id="detailId"></span><br>
                                    <label>Rack:</label> <span id="detailRack"></span>
                                </div>
                                <div class="col-md-6">
                                    <a id="detailEstimate" href="" target="_blank" type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Generate Estimate">
                                        <i class="glyphicon glyphicon-list-alt"></i>
                                    </a>
                                    <a id="detailPDF" href="" target="_blank" type="button" class="btn btn-primary btn-sm hidden" data-toggle="tooltip" data-placement="top" title="Generate PDF">
                                        <i class="glyphicon glyphicon-file"></i>
                                    </a>
                                    <a id="detailUpdate" href="" type="button" class="btn btn-primary btn-sm hidden" data-toggle="tooltip" data-placement="top" title="Update record">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    <button id="detailView" type="button" class="btn btn-primary btn-sm" style="background-color:#6f5499!important" data-toggle="tooltip" data-placement="top" title="View record">
                                        <i class="glyphicon glyphicon-eye-open"></i>
                                    </button>
                                    <button id="detailProcess" type="button" class="btn btn-success btn-sm hidden" data-toggle="tooltip" data-placement="top" title="Process record">
                                        <i class="glyphicon glyphicon-tasks"></i>
                                    </button>
                                    <button id="detailRelease" type="button" class="btn btn-warning btn-sm hidden" data-toggle="tooltip" data-placement="top" title="Release">
                                        <i class="glyphicon glyphicon-export"></i>
                                    </button>
                                    <button id="detailFinalize" type="button" class="btn btn-success btn-sm hidden" data-toggle="tooltip" data-placement="top" title="Finalize record">
                                        <i class="glyphicon glyphicon-check"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 pull-left">
                                    <label>Start:</label><br><span id="detailStart"></span>
                                </div>
                                <div class="col-md-6 pull-right">
                                    <label>End:</label><br><span id="detailEnd"></span>
                                </div>
                            </div>
                            <label>Vehicle:</label><br>
                            <ul>
                                <li>Plate: <span id="detailPlate"></span></li>
                                <li>Model: <span id="detailModel"></span></li>
                                <li>Mileage: <span id="detailMileage"></span></li>
                            </ul>
                            <label>Customer:</label> <span id="detailCustomer"></span><br>
                            <label>Technician(s):</label>
                            <ul id="detailTechs"></ul>
                        </div>
                    </div>
                </div>
                {{-- CALENDAR/DATATABLE --}}
                <div class="col-md-8">
                    <div class="tab-content">
                        {{-- CALENDAR --}}
                        <div role="tabpanel" class="tab-pane fade in active" id="calendarTab">
                            <div class="box box-primary">
                                <div class="box-body no-padding">
                                    <div id="calendar"></div>
                                </div>
                            </div>
                        </div>
                        {{-- DATATABLE --}}
                        <div role="tabpanel" class="tab-pane fade" id="tabularTab">
                            <div class="box box-primary">
                                <div class="box-body dataTable_wrapper">
                                    <table id="list" class="table table-striped table-bordered responsive">
                                        <thead>
                                            <tr>
                                                <th>Vehicle</th>
                                                <th>Customer</th>
                                                <th class="text-right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($jobs as $job)
                                                <tr>
                                                    <td>
                                                        <li>Plate: {{$job->plate}}</li>
                                                        <?php $transmission = ($job->transmission ? 'MT' : 'AT')?>
                                                        <li>Model: {{$job->make}} - {{$job->year}} {{$job->model}} - {{$transmission}}</li>
                                                        @if($job->mileage!=null)
                                                        <li>Mileage: {{$job->mileage}}</li>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <li>Name: {{$job->firstName}} {{$job->middleName}} {{$job->lastName}}</li>
                                                        <li>Address: {{$job->street}} {{$job->brgy}} {{$job->city}}</li>
                                                        <li>Contact No.: {{$job->contact}}</li>
                                                        @if($job->email!=null)
                                                        <li>Email: {{$job->email}}</li>
                                                        @endif
                                                        @if($job->card!=null)
                                                        <li>Senior Citizen/PWD ID: {{$job->card}}</li>
                                                        @endif
                                                    </td>
                                                    <td class="text-right">
                                                        @if(!$job->isFinalize)
                                                            <button onclick="finalizeModal('{{$job->jobId}}')" type="button" class="btn btn-success btn-sm"  data-toggle="tooltip" data-placement="top" title="Finalize record">
                                                                <i class="glyphicon glyphicon-check"></i>
                                                            </button>
                                                            <a href="{{url('/job/'.$job->jobId.'/edit')}}" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Update record">
                                                                <i class="glyphicon glyphicon-edit"></i>
                                                            </a>
                                                            {!! Form::open(['method'=>'patch','action' => ['JobController@finalize',$job->jobId],'id'=>'fin'.$job->jobId]) !!}
                                                            {!! Form::close() !!}
                                                        @else
                                                            <a href="{{url('/job/pdf/'.$job->jobId)}}" target="_blank" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Generate PDF">
                                                                <i class="glyphicon glyphicon-file"></i>
                                                            </a>
                                                            <button onclick="process('{{$job->jobId}}')" type="button" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Process record">
                                                                <i class="glyphicon glyphicon-tasks"></i>
                                                            </button>
                                                            {!! Form::open(['method'=>'patch','action' => ['JobController@release',$job->jobId],'id'=>'rel'.$job->jobId]) !!}
                                                            {!! Form::close() !!}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--  CREATE  --}}
            <div id="jobForm" class="item">
                {!! Form::open(['url' => 'job']) !!}
                @include('layouts.required')
                {!! Form::hidden('start',null,[
                    'class' => 'form-control',
                    'id' => 'start']) 
                !!}
                @include('job.formCreate')
                {!! Form::close() !!}
            </div>
            {{--  PROCESS  --}}
            <div id="processForm" class="item">
                {!! Form::open(['method'=>'post','action' => ['JobController@process',0]]) !!}
                <input id="processId" name="id" type="hidden">
                <div class="col-md-12">
                    <button id="backProcess" type="button" class="btn btn-success btn-md pull-left"><i class="fa fa-angle-double-left"></i> Back</button>
                </div>
                <br><br>
                {{-- CUSTOMER DETAILS --}}
                <div class="col-md-4">
                    <div id="infoBox" class="box box-success">
                        <div class="box-header with border">
                            <h3 class="box-title">Customer Details</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6 pull-left">
                                    <label>Start:</label><br><span class="pro" id="processStart"></span>
                                </div>
                                <div class="col-md-6 pull-right">
                                    <label>End:</label><br><span class="pro" id="processEnd"></span>
                                </div>
                            </div>
                            <label>Vehicle:</label><br>
                            <ul>
                                <li>Plate: <span class="pro" id="processPlate"></span></li>
                                <li>Model: <span class="pro" id="processModel"></span></li>
                                <li>Mileage: <span class="pro" id="processMileage"></span></li>
                            </ul>
                            <label>Customer:</label> <span class="pro" id="processCustomer"></span><br>
                            <label>Technician(s):</label>
                            <ul id="processTechs"></ul>
                        </div>
                    </div>
                </div>
                {{--  PROGRESS DETAILS  --}}
                <div class="col-md-8">
                    <div id="processBox" class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Job Order Details</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            {{-- PAYMENT --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group form-inline">
                                        {!! Form::label('computed', 'Balance:') !!}
                                        <strong>{!! Form::input('text','computed',0,[
                                            'class' => 'form-control no-border-input',
                                            'id' => 'balance',
                                            'readonly'])
                                        !!}</strong>
                                    </div>
                                    <a style="color:black;font-weight:600" role="button" data-toggle="collapse" href="#listPayments" aria-expanded="false" aria-controls="listPayments">View Payment Details <i class="fa fa-caret-down"></i></a>
                                </div>
                                <div class="col-md-6 addPayment">
                                    <div class="form-group form-inline">
                                        {!! Form::label('inputPayment', 'Add Payment: ',['class'=>'addPayment']) !!}
                                        <input type="hidden" id="paymentMethod" value="0">
                                        {!! Form::input('text','inputPayment',null,[
                                            'id'=>'inputPayment',
                                            'class' => 'form-control',
                                            'placeholder'=>'Payment']) 
                                        !!}
                                    </div>
                                    <div id="creditCard" class="form-group hidden">
                                        {!! Form::label('inputCredit', 'Credit Card: ') !!}
                                        {!! Form::input('text','inputCredit',null,[
                                            'id'=>'inputCredit',
                                            'class' => 'form-control',
                                            'placeholder'=>'Credit']) 
                                        !!}
                                    </div>
                                    <button type="button" id="savePayment" class="btn btn-info btn-md">Add Payment</button>
                                </div>
                                <div class="col-md-12">
                                    <div class="collapse" id="listPayments">
                                        <label>Total Price: </label>
                                        <strong>{!! Form::input('text','totalPrice',0,[
                                            'class' => 'prices',
                                            'id' => 'totalPrice',
                                            'style' => 'border: none!important;background: transparent!important',
                                            'readonly']) 
                                        !!}</strong>
                                        <div class="dataTable_wrapper">
                                            <label>Payments:</label>
                                            <table id="paymentList" class="table table-striped table-bordered responsive">
                                                <thead>
                                                    <tr>
                                                        <th width="5%" class="text-right">Amount (PhP)</th>
                                                        <th width="5%">Method</th>
                                                        <th class="text-right">Date</th>
                                                        <th class="text-right">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <label>Progress:</label>
                            <div class="progress">
                                <div id="progress-bar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;width: 0%;">
                                    0%
                                </div>
                            </div><br>
                            <div class="dataTable_wrapper">
                                <table id="processList" class="table table-striped table-bordered responsive">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th width="10%" class="text-right">Quantity</th>
                                            <th width="10%" class="text-right">Completed</th>
                                            <th class="text-right">Status</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            {{-- Viewing --}}
            <div id="viewModal" class="modal fade bs-example-modal-lg">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">View Job Order</h4>
                        </div>
                        <div class="modal-body">
                            <div class="col-md-4">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h2 class="panel-title">Customer Details</h2>
                                    </div>
                                    <div class="panel-body">
                                        <label>Job Id:</label> <span id="viewId"></span><br>
                                        <label>Rack:</label> <span id="viewRack"></span>
                                        <div class="row">
                                            <div class="col-md-6 pull-left">
                                                <label>Start:</label><br><span id="viewStart"></span>
                                            </div>
                                            <div class="col-md-6 pull-right">
                                                <label>End:</label><br><span id="viewEnd"></span>
                                            </div>
                                        </div>
                                        <label>Vehicle:</label><br>
                                        <ul>
                                            <li>Plate: <span id="viewPlate"></span></li>
                                            <li>Model: <span id="viewModel"></span></li>
                                            <li>Mileage: <span id="viewMileage"></span></li>
                                        </ul>
                                        <label>Customer:</label> <span id="viewCustomer"></span><br>
                                        <label>Technician(s):</label>
                                        <ul id="viewTechs"></ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h2 class="panel-title">Progress Details</h2>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group form-inline">
                                            {!! Form::label('computed', 'Balance:') !!}
                                            <strong>{!! Form::input('text','computed',0,[
                                                'class' => 'form-control no-border-input',
                                                'id' => 'viewBalance',
                                                'readonly'])
                                            !!}</strong>
                                        </div>
                                        <a style="color:black;font-weight:600" role="button" data-toggle="collapse" href="#viewPayments" aria-expanded="false" aria-controls="viewPayments">View Payment Details <i class="fa fa-caret-down"></i></a>
                                        <br><div class="col-md-12">
                                            <div class="collapse" id="viewPayments">
                                                <label>Total Price: </label>
                                                <strong>{!! Form::input('text','viewPrice',0,[
                                                    'class' => 'prices',
                                                    'id' => 'viewPrice',
                                                    'style' => 'border: none!important;background: transparent!important',
                                                    'readonly']) 
                                                !!}</strong>
                                                <div class="dataTable_wrapper">
                                                    <label>Payments:</label>
                                                    <table id="paymentView" class="table table-striped table-bordered responsive">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%" class="text-right">Amount (PhP)</th>
                                                                <th width="5%">Method</th>
                                                                <th class="text-right">Date</th>
                                                                <th class="text-right">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <label>Progress:</label>
                                        <div class="progress">
                                            <div id="progress-view" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;width: 0%;">
                                                0%
                                            </div>
                                        </div>
                                        <div class="dataTable_wrapper">
                                            <table id="processView" class="table table-striped table-bordered responsive">
                                                <thead>
                                                    <tr>
                                                        <th>Item</th>
                                                        <th width="10%" class="text-right">Quantity</th>
                                                        <th width="10%" class="text-right">Completed</th>
                                                        <th class="text-right">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Release --}}
            <div id="releaseModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Release</h4>
                        </div>
                        <div class="modal-body">
                            <div style="text-align:center">Are you sure you want to release this vehicle?</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            <button id="release" type="button" class="btn btn-warning">Release</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Finalize --}}
            <div id="finalizeModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Finalize</h4>
                        </div>
                        <div class="modal-body">
                            <div style="text-align:center">Are you sure you want to finalize this record?</div>
                            <br>
                            <div class="dataTable_wrapper">
                                <table id="finalizeList" class="table table-striped table-bordered responsive">
                                    <thead>
                                        <tr>
                                            <th width="10%" class="text-right">Quantity</th>
                                            <th>Item</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            <button id="finalize" type="button" class="btn btn-success">Finalize</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Technician --}}
            <div id="techModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">List of Technicians</h4>
                        </div>
                        <div class="modal-body">
                            <div class="dataTable_wrapper">
                                <table id="techList" class="table table-striped table-bordered responsive">
                                    <thead>
                                        <tr>
                                            <th>Technician</th>
                                            <th>Skills</th>
                                            <th>On going tasks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($technicians as $tech)
                                        <tr>
                                            <td>{{$tech->firstName}} {{$tech->middleName}} {{$tech->lastName}}</td>
                                            <td>
                                                @foreach($tech->skill as $skill)
                                                    <li>{{$skill->category->name}}</li>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($tech->job as $task)
                                                    @if($task->header->release==null && $task->header->isFinalize)
                                                        <li>{{'JOB'.str_pad($task->header->id, 5, '0', STR_PAD_LEFT)}}</li>
                                                    @endif
                                                @endforeach
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{ URL::asset('assets/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fullcalendar/moment.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.numeric.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.phone.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ URL::asset('js/customer.js') }}"></script>
    <script src="{{ URL::asset('js/techList.js') }}"></script>
    <script src="{{ URL::asset('js/item.js') }}"></script>
    <script src="{{ URL::asset('js/jobFinal.js') }}"></script>
    <script src="{{ URL::asset('js/jobProcess.js') }}"></script>
    <script src="{{ URL::asset('js/jobCalendar.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#list').DataTable({
                responsive: true,
            });
            $('#tJob').addClass('active');
            var customers = [
                @foreach($customers as $customer)
                    '{{$customer->firstName}} {{$customer->middleName}} {{$customer->lastName}}',
                @endforeach
            ];    
            var activeTechnicians = [
                @if(old('technician'))
                    @foreach(old('technician') as $technician)
                        "{{$technician}}",
                    @endforeach
                @endif
            ];
            @if(!$jobs->isEmpty())
                @foreach($jobs as $job)
                    var events = {
                        id: {{$job->jobId}},
                        title: '{{$job->plate}}',
                        start: '{{$job->start}}',
                        end: '{{$job->release}}',
                        @if($job->isComplete && $job->total==$job->paid && $job->release!=null)
                            color: '#6f5499'
                        @elseif($job->isComplete && $job->total==$job->paid)
                            //success
                            color: '#00a65a'
                        @elseif(!$job->isComplete && $job->isFinalize && $job->total==$job->paid)
                            //info
                            color: '#00c0ef'
                        @elseif(!$job->isComplete && $job->isFinalize && $job->total!=$job->paid)
                            //warning
                            color: '#f39c12'
                        @else
                            //primary
                            color: '#3c8dbc'
                        @endif
                    };
                    $('#calendar').fullCalendar('renderEvent', events, true);
                @endforeach
            @endif
            $("#technician").val(activeTechnicians);
            @if(old('rackId'))
                $("#rack").val({{old('rackId')}});
            @endif
            @if(old('modelId'))
                $("#model").val({{old('modelId')}});
            @endif
            $(".select2").select2();
            $('#firstName').autocomplete({source: customers});
            @if(!old('contact'))
                $('#contact').inputmask("+63 999 9999 999");
            @else
                $('#jobCarousel').carousel(1);
                @if(old('contact')[2] == '2' && strlen(old('contact')) >= 17)
                    $('#contact').inputmask("(02) 999 9999 loc. 9999");
                @elseif(old('contact')[2] == '2')
                    $('#contact').inputmask("(02) 999 9999");
                @else
                    $('#contact').inputmask("+63 999 9999 999");
                @endif
            @endif
            @if(!old('plate'))
                $('#plate').inputmask("AAA 999");
            @else
                @if(strlen(old('plate')) == 7)
                    $('#plate').inputmask("AAA 999");
                @elseif(strlen(old('plate')) == 8)
                    $('#plate').inputmask("AAA 9999");
                @elseif(strlen(old('plate')) == 6)
                    @if(old('plate')[3] != ' ')
                        $('#plate').inputmask("AA 9999");
                    @else
                        $('#plate').inputmask("AAA 99");
                    @endif
                @elseif(strlen(old('plate')) == 1)
                    $('#plate').inputmask("9");
                @else
                    $('#plate').inputmask();
                    $('#plate').val("For Registration");
                @endif
            @endif
        });
    </script>
    @include('layouts.oldItems')
    <script>
        if(isVat){
            $("#vatRate").inputmask({ 
                alias: "percentage",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
                max: 100,
            });
            $('#vatStack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: true,
                min: 0,
            });
        }
    </script>
@stop
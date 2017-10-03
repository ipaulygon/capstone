@extends('layouts.master')

@section('title')
    {{"Obtain Warranty"}}
@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/pace/pace.min.css') }}">
@endsection

@section('content')
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"></h3>
                <ul class="nav nav-tabs" id="mainTab">
                    <li class="active">
                        <a href="#sales" id="mainTabSales" data-toggle="tab">Sales</a>
                    </li>
                    <li>
                        <a href="#jobs" id="mainTabJobs" data-toggle="tab">Jobs</a>
                    </li>
                </ul>
            </div>
            <div class="box-body dataTable_wrapper">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="sales">
                        <table id="salesTable" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>Invoice No.</th>
                                    <th>Description</th>
                                    <th class="text-right">Price (PhP)</th>
                                    <th>Date</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sales as $sale)
                                    <tr>
                                        <td>{{'INV'.str_pad($sale->id, 5, '0', STR_PAD_LEFT)}}</td>
                                        <td>
                                            @foreach($sale->product as $product)
                                                <?php
                                                    if($product->product->isOriginal!=null){
                                                        $type = ($product->product->isOriginal=="type1" ? $util->type1 : $util->type2);
                                                    }else{
                                                        $type = "";
                                                    }
                                                ?>
                                                <li>{{$product->product->brand->name}} - {{$product->product->name}} {{$type}} ({{$product->product->variance->name}}) x {{$product->quantity}} pcs.</li>
                                            @endforeach
                                            @foreach($sale->package as $package)
                                                <li>{{$package->package->name}}  x {{$package->quantity}} pcs.</li>
                                            @endforeach
                                            @foreach($sale->promo as $promo)
                                                <li>{{$promo->promo->name}}  x {{$promo->quantity}} pcs.</li>
                                            @endforeach
                                            @if($sale->discount)
                                                <li>{{$sale->discount->discount->name}} Discount</li>
                                            @endif
                                        </td>
                                        <td class="text-right">{{number_format($sale->total,2)}}</td>
                                        <td>{{date('F j, Y - H:i:s',strtotime($sale->created_at))}}</td>
                                        <td class="text-right">
                                            <button id="salesObtain" data-id="{{$sale->id}}" type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Obtain Warranty"><i class="fa fa-level-down"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- SALES MODAL -->
                    <div id="salesModal" class="modal fade">
                        <div class="modal-dialog modal-lg">
                            {!! Form::open(['url' => 'warranty','id' => 'salesForm']) !!}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span></button>
                                    <h4 class="modal-title">Obtain Warranty <i id="infoInventory" class="fa fa-question-circle"></i></h4>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="salesId" name="salesId"> 
                                    <div class="row">
                                        <div class="col-md-12" id="salesError"></div>
                                        <div class="col-md-6 col-md-offset-3 dataTable_wrapper">
                                            <label>Products:</label>
                                            <table id="salesProduct" class="table table-striped table-bordered responsive">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th class="text-right">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6 dataTable_wrapper">
                                            <label>Packages:</label>
                                            <table id="salesPackage" class="table table-striped table-bordered responsive">
                                                <thead>
                                                    <tr>
                                                        <th>Package</th>
                                                        <th class="text-right">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6 dataTable_wrapper">
                                            <label>Promos:</label>
                                            <table id="salesPromo" class="table table-striped table-bordered responsive">
                                                <thead>
                                                    <tr>
                                                        <th>Promo</th>
                                                        <th class="text-right">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <h4>Warranty Items</h4>
                                    <div class="dataTable_wrapper">
                                        <table id="salesList" class="table table-striped table-bordered responsive">
                                            <thead>
                                                <tr>
                                                    <th class="text-right">Quantity</th>
                                                    <th>Product</th>
                                                    <th>Belongs to:(Package/Promo)</th>
                                                    <th class="text-right">Quantity to apply warranty</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                    {!! Form::submit('Submit', ['class'=>'btn btn-primary','id'=>'salesSubmit']) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="jobs">
                        <table id="jobsTable" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>Job Details</th>
                                    <th>Description</th>
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
                                        <button id="jobsObtain" data-id="{{$sale->id}}" type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Obtain Warranty"><i class="fa fa-level-down"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- JOBS MODAL -->
                    <div id="jobModal" class="modal fade">
                        <div class="modal-dialog modal-lg">
                            {!! Form::open(['url' => 'warranty','id' => 'jobForm']) !!}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span></button>
                                    <h4 class="modal-title">Obtain Warranty <i id="infoInventory" class="fa fa-question-circle"></i></h4>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="jobId" name="jobId"> 
                                    <div class="row">
                                        <div class="col-md-12" id="jobsError"></div>
                                        <div class="col-md-6 dataTable_wrapper">
                                            <label>Products:</label>
                                            <table id="jobProduct" class="table table-striped table-bordered responsive">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th class="text-right">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6 dataTable_wrapper">
                                            <label>Services:</label>
                                            <table id="jobService" class="table table-striped table-bordered responsive">
                                                <thead>
                                                    <tr>
                                                        <th>Service</th>
                                                        <th class="text-right">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6 dataTable_wrapper">
                                            <label>Packages:</label>
                                            <table id="jobPackage" class="table table-striped table-bordered responsive">
                                                <thead>
                                                    <tr>
                                                        <th>Package</th>
                                                        <th class="text-right">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6 dataTable_wrapper">
                                            <label>Promos:</label>
                                            <table id="jobPromo" class="table table-striped table-bordered responsive">
                                                <thead>
                                                    <tr>
                                                        <th>Promo</th>
                                                        <th class="text-right">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <h4>Warranty Items</h4>
                                    <div class="dataTable_wrapper">
                                        <table id="jobList" class="table table-striped table-bordered responsive">
                                            <thead>
                                                <tr>
                                                    <th class="text-right">Quantity</th>
                                                    <th>Item</th>
                                                    <th>Belongs to:(Package/Promo)</th>
                                                    <th class="text-right">Quantity to apply warranty</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                    {!! Form::submit('Submit', ['class'=>'btn btn-primary','id'=>'jobsSubmit']) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.inventoryList')
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/datatables/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.numeric.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ URL::asset('js/inventoryList.js') }}"></script>
    <script src="{{ URL::asset('js/swarranty.js') }}"></script>
    <script src="{{ URL::asset('js/jwarranty.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#salesTable').DataTable({
                responsive: true,
            });
            $('#jobsTable').DataTable({
                responsive: true,
            });
            $('#tWarranty').addClass('active');
            $('#mainTab a[href=#{{$tab}}]').tab('show');
        });
        $(document).on('click','#mainTabSales',function(){
            $.ajax({
                type: 'GET',
                url: 'warranty/tab/sales',
                dataType: 'JSON',
                success: function(data){
                    console.log(data);
                }
            });
        })
        $(document).on('click','#mainTabJobs',function(){
            $.ajax({
                type: 'GET',
                url: 'warranty/tab/jobs',
                dataType: 'JSON',
                success: function(data){
                    console.log(data);
                }
            });
        })
    </script>
@endsection
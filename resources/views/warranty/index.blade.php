@extends('layouts.master')

@section('title')
    {{"Warranty/Void Transactions"}}
@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
@endsection

@section('content')
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"></h3>
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#sales" data-toggle="tab">Sales</a>
                    </li>
                    <li>
                        <a href="#jobs" data-toggle="tab">Jobs</a>
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
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sales as $sale)
                                    <tr>
                                        <?php 
                                            $salesId = 'INV'.str_pad($sale->id, 5, '0', STR_PAD_LEFT); 
                                        ?>
                                        <td>{{$salesId}}</td>
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
                                            
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="jobs">
                        <table id="salesTable" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>Invoice No.</th>
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
                                        <td class="text-right"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/datatables/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#salesTable').DataTable({
                responsive: true,
            });
            $('#jobsTable').DataTable({
                responsive: true,
            });
            $('#tWarranty').addClass('active');
        });
    </script>
@endsection
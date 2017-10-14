@extends('layouts.master')

@section('title')
    {{"Dashboard"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
    <style>
        .info-box{
            cursor: pointer;
        }
    </style>
@stop

@section('content')
    @if($user->type==1)
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-4">
                <div class="info-box bg-aqua" data-link="job">
                    <span class="info-box-icon"><i class="fa fa-wrench"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-number text-center" style="font-size:3em">{{count($jobs)}}</span>
                        <span class="info-box-text text-center" style="font-size:1em">JOB ORDERS</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-green" data-link="sales/create">
                    <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-number text-center" style="font-size:3em">{{count($sales)}}</span>
                        <span class="info-box-text text-center" style="font-size:1em">SALES</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-red" data-link="damage">
                    <span class="info-box-icon"><i class="fa fa-trash"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-number text-center" style="font-size:3em">0</span>
                        <span class="info-box-text text-center" style="font-size:1em">DISCREPANCIES</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    {{-- Jobs --}}
    <div class="col-md-7">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Ongoing Jobs</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <table id="listJobs" class="table table-striped table-bordered responsive">
                    <thead>
                        <tr>
                            <th>Job</th>
                            <th>Description</th>
                            <th>Technician</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingJobs as $job)
                        <tr>
                            <td>{{'JOB'.str_pad($job->id, 5, '0', STR_PAD_LEFT)}}</td>
                            <td>
                                Customer: {{$job->customer->firstName}} {{$job->customer->lastName}}<br>
                                Vehicle: {{$job->vehicle->plate}}
                                <li>{{$job->vehicle->model->make->name}} - {{$job->vehicle->model->name}} ({{($job->vehicle->isManual ? 'MT' : 'AT')}})</li>
                            </td>
                            <td>
                                @foreach($job->technician as $technician)
                                    <li>{{$technician->technician->firstName}} {{$technician->technician->lastName}}</li>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
            <div class="box-body dataTable_wrapper">
                <table id="listStock" class="table table-striped table-bordered responsive">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-right">Stock</th>
                            <th class="text-right">Threshold</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stocks as $product)
                        <tr>
                            <?php
                                if($product->isOriginal!=null){
                                    $type = ($product->isOriginal=="type1" ? $util->type1 : $util->type2);
                                }else{
                                    $type = "";
                                }
                            ?>
                            <td>{{$product->brand}} - {{$product->product}} {{$type}} ({{$product->variance}})</td>
                            <td class="text-right">{{number_format($product->quantity)}}</td>
                            <td class="text-right">{{number_format($product->reorder)}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- end of component --}}
    @else
    {{-- Jobs --}}
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Ongoing Jobs</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <table id="listJobs" class="table table-striped table-bordered responsive">
                    <thead>
                        <tr>
                            <th>Job</th>
                            <th>Description</th>
                            <th>To Do</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingJobs as $job)
                        <tr>
                            @foreach($job->technician as $tech)
                                @if($tech->technicianId==$techUser->id)
                                    <td>{{'JOB'.str_pad($job->id, 5, '0', STR_PAD_LEFT)}}</td>
                                    <td>
                                        Customer: {{$job->customer->firstName}} {{$job->customer->lastName}}<br>
                                        Vehicle: {{$job->vehicle->plate}}
                                        <li>{{$job->vehicle->model->make->name}} - {{$job->vehicle->model->name}} ({{($job->vehicle->isManual ? 'MT' : 'AT')}})</li>
                                    </td>
                                    <td>
                                        @foreach($job->product as $product)
                                            @if(!$product->isComplete)
                                                <?php
                                                    if($product->product->isOriginal!=null){
                                                        $type = ($product->product->isOriginal=="type1" ? $util->type1 : $util->type2);
                                                    }else{
                                                        $type = "";
                                                    }
                                                    $discount = null;
                                                    if($product->product->discount!=null){
                                                        $discount = $product->product->discount->header->rateRecord->where('created_at','<=',$job->created_at)->first()->rate;
                                                    }else{
                                                        $dis = $product->product->discountRecord->where('created_at','<=',$job->created_at)->where('updated_at','>=',$job->created_at);
                                                        if(count($dis) > 0){
                                                            $discount = $dis->first()->header->rateRecord->where('created_at','<=',$job->created_at)->first()->rate;
                                                        }
                                                    }
                                                    $price = $product->product->priceRecord->where('created_at','<=',$job->created_at)->first()->price;
                                                    if($discount!=null){
                                                        $price = $price-($price*($discount/100));
                                                        $discountString = '['.$discount.' % discount]';
                                                    }else{
                                                        $discountString = '';
                                                    }
                                                ?>
                                                <li>{{$product->product->brand->name}} - {{$product->product->name}} {{$type}} ({{$product->product->variance->name}}) {{$discountString}} x {{number_format($product->quantity)}} pcs.</li>
                                            @endif
                                        @endforeach
                                        @foreach($job->service as $service)
                                            @if(!$service->isComplete)
                                                <?php
                                                    $discount = null;
                                                    if($service->service->discount!=null){
                                                        $discount = $service->service->discount->header->rateRecord->where('created_at','<=',$job->created_at)->first()->rate;
                                                    }else{
                                                        $dis = $service->service->discountRecord->where('created_at','<=',$job->created_at)->where('updated_at','>=',$job->created_at);
                                                        if(count($dis) > 0){
                                                            $discount = $dis->first()->header->rateRecord->where('created_at','<=',$job->created_at)->first()->rate;
                                                        }
                                                    }
                                                    $price = $service->service->priceRecord->where('created_at','<=',$job->created_at)->first()->price;
                                                    if($discount!=null){
                                                        $price = $price-($price*($discount/100));
                                                        $discountString = '['.$discount.' % discount]';
                                                    }else{
                                                        $discountString = '';
                                                    }
                                                ?>
                                                <li>{{$service->service->name}} - {{$service->service->size}} ({{$service->service->category->name}}) {{$discountString}}</li>
                                            @endif
                                        @endforeach
                                        @foreach($job->package as $package)
                                            @if(!$package->isComplete)
                                                <li>{{$package->package->name}} x {{number_format($package->quantity)}} pcs.</li>
                                            @endif
                                        @endforeach
                                        @foreach($job->promo as $promo)
                                            @if(!$promo->isComplete)
                                                <li>{{$promo->promo->name}} x {{number_format($promo->quantity)}} pcs.</li>
                                            @endif
                                        @endforeach
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
@stop

@section('script')
    <script src="{{ URL::asset('assets/datatables/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#listStock').DataTable({
                responsive: true,
            });
            $('#listJobs').DataTable({
                responsive: true,
            });
            $('#dashboard').addClass('active');
        });
        $(document).on('click','.info-box',function(){
            link = $(this).attr('data-link');
            window.location.replace('/'+link);
        });
    </script>
@stop
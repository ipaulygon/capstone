@extends('layouts.master')

@section('title')
    {{"Queries"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
@stop

@section('content')
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"> </h3>
            </div>
            <div class="box-body dataTable_wrapper">
                <form action="">
                    <div class="form-group">
                        {!! Form::label('Query', 'Query Search') !!}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            <select id="queryId" name="queryId" class="form-control">
                                <option value=""></option>
                                <option value="1">Most availed products</option>
                                <option value="2">Most availed services</option>
                                <option value="3">Most jobs done by technician</option>
                                <option value="4">Most repaired vehicle</option>
                                <option value="5">Customers with pending payments</option>
                                <option value="6">Current Inventory List</option>
                            </select>
                        </div>
                    </div>
                </form>
                <div class="panel panel-primary hidden pan1">
                    <div class="panel-heading"></div>
                    <div class="panel-body">
                        <table id="list1" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Description</th>
                                    <th class="text-right">No. of times availed</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>{{$product->brand}} - {{$product->product}}</td>
                                    <td>
                                        <li>Type: {{$product->type}}</li>
                                        <li>Size: {{$product->variance}}</li>
                                        @if($product->original!=null)
                                            <?php $type = ($product->original=="type1" ? $util->type1 : $util->type2); ?>
                                            <li>Part Information: {{$type}}</li>
                                        @endif
                                        @if($product->description!=null || $product->description!="")
                                            <li>{{$product->description}}</li>
                                        @endif
                                    </td>
                                    <td class="text-right">{{$product->total}}</td>
                                </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-primary hidden pan2">
                    <div class="panel-heading"></div>
                    <div class="panel-body">
                        <table id="list2" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Description</th>
                                    <th class="text-right">No. of times availed</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($services as $service)
                                <tr>
                                    <td>{{$service->service}}</td>
                                    <td>
                                        <li>Category: {{$service->category}}</li>
                                        <li>Size: {{$service->size}}</li>
                                    </td>
                                    <td class="text-right">{{$service->total}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-primary hidden pan3">
                    <div class="panel-heading"></div>
                    <div class="panel-body">
                        <table id="list3" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>Picture</th>
                                    <th>Technician</th>
                                    <th>Details</th>
                                    <th class="text-right">No. of times jobs done</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($technicians as $tech)
                                <tr>
                                    <td><img class="img-responsive" src="{{URL::asset($tech->image)}}" alt="" style="max-width:150px; background-size: contain"></td>
                                    <td>{{$tech->firstName}} {{$tech->middleName}} {{$tech->lastName}}</td>
                                    <td>
                                        <?php
                                            $date = date_create($tech->birthdate);
                                            $date = date_format($date,"F d,Y");
                                        ?>
                                        <li>Birthdate: {{$date}}</li>
                                        <li>Contact: {{$tech->contact}}</li>
                                        <li>Address: {{$tech->street}} {{$tech->brgy}} {{$tech->city}}</li>
                                        @if($tech->email)
                                        <li>Email: {{$tech->email}}</li>
                                        @endif
                                        {{-- <li>Skills:</li>
                                        <ul>
                                            @foreach($tech->skill as $skills)
                                                <li>{{$skills->category->name}}</li>
                                            @endforeach
                                        </ul> --}}
                                    <td class="text-right">{{$tech->total}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-primary hidden pan4">
                    <div class="panel-heading"></div>
                    <div class="panel-body">
                        <table id="list4" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th class="text-right">No. of times repaired</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vehicles as $vehicle)
                                <tr>
                                    <td>
                                        <li>Plate: {{$vehicle->plate}}</li>
                                        <?php $transmission = ($vehicle->isManual ? 'MT' : 'AT')?>
                                        <li>Model: {{$vehicle->make}} - {{$vehicle->year}} {{$vehicle->model}} - {{$transmission}}</li>
                                        @if($vehicle->mileage!=null)
                                        <li>Mileage: {{$vehicle->mileage}}</li>
                                        @endif
                                    </td>
                                    <td class="text-right">{{$vehicle->total}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-primary hidden pan5">
                    <div class="panel-heading"></div>
                    <div class="panel-body">
                        <table id="list5" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th class="text-right">Amount Due</th>
                                    <th class="text-right">Amount Paid</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($customers as $customer)
                                <tr>
                                    <td>
                                        <li>Name: {{$customer->firstName}} {{$customer->middleName}} {{$customer->lastName}}</li>
                                        <li>Address: {{$customer->street}} {{$customer->brgy}} {{$customer->city}}</li>
                                        <li>Contact No.: {{$customer->contact}}</li>
                                        @if($customer->email!=null)
                                        <li>Email: {{$customer->email}}</li>
                                        @endif
                                    </td>
                                    <td class="text-right">{{number_format($customer->total,2)}}</td>
                                    <td class="text-right">{{number_format($customer->paid,2)}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-primary hidden pan6">
                    <div class="panel-heading"></div>
                    <div class="panel-body">
                        <table id="list6" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-right">Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($inventory as $product)
                                <?php
                                    if($product->isOriginal!=null){
                                        $type = ($product->isOriginal=="type1" ? $util->type1 : $util->type2);
                                    }else{
                                        $type = "";
                                    }
                                ?>
                                <tr>
                                    <td>{{$product->brand}} - {{$product->product}} {{$type}} ({{$product->variance}})</td>
                                    <td class="text-right">{{$product->quantity}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{ URL::asset('assets/datatables/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script>
        var pan = null;
        $(document).ready(function (){
            $('#query').addClass('active');
            $('#list1').DataTable({
                responsive: true
            });
            $('#list2').DataTable({
                responsive: true
            });
            $('#list3').DataTable({
                responsive: true
            });
            $('#list4').DataTable({
                responsive: true
            });
            $('#list5').DataTable({
                responsive: true
            });
            $('#list6').DataTable({
                responsive: true
            });
        });
        $('#queryId').on('change', function() {          
            if(pan!=null){
                $(pan).addClass('hidden');
            }
            pan = $('.pan'+$(this).val()).removeClass('hidden');
        });
    </script>
@stop
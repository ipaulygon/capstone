@extends('layouts.master')

@section('title')
    {{"Job Order"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/pace/pace.min.css') }}">
@stop

@section('content')
    {!! Form::model($job , ['method' => 'patch', 'action' => ['JobController@update',$job->id]]) !!}
    @include('layouts.required')
    @include('job.formEdit')
    {!! Form::close() !!}
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
@stop

@section('script')
    <script src="{{ URL::asset('assets/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.numeric.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.phone.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ URL::asset('js/customer.js') }}"></script>
    <script src="{{ URL::asset('js/techList.js') }}"></script>
    <script src="{{ URL::asset('js/item.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#tJob').addClass('active');
            var customers = [
                @foreach($customers as $customer)
                    '{{$customer->firstName}} {{$customer->middleName}} {{$customer->lastName}}',
                @endforeach
            ];    
            var activeTechnicians = [
                @foreach($job->technician as $technician)
                    '{{$technician->technicianId}}',
                @endforeach
            ]; 
            $("#technician").val(activeTechnicians);
            $("#rack").val({{$job->rackId}});
            $("#model").val({{$job->vehicle->modelId}}+','+{{$job->vehicle->isManual}});
            $(".select2").select2();
            $('#firstName').autocomplete({source: customers});
            @if($job->customer->contact[2] == '2' && strlen($job->customer->contact) >= 17)
                $('#contact').inputmask("(02) 999 9999 loc. 9999");
            @elseif($job->customer->contact[2] == '2')
                $('#contact').inputmask("(02) 999 9999");
            @else
                $('#contact').inputmask("+63 999 9999 999");
            @endif
            @if(strlen($job->vehicle->plate) == 7)
                $('#plate').inputmask("AAA 999");
            @elseif(strlen($job->vehicle->plate)== 8)
                $('#plate').inputmask("AAA 9999");
            @elseif(strlen($job->vehicle->plate) == 6)
                @if($job->vehicle->plate[3] != ' ')
                    $('#plate').inputmask("AA 9999");
                @else
                    $('#plate').inputmask("AAA 99");
                @endif
            @elseif(strlen($job->vehicle->plate) == 1)
                $('#plate').inputmask("9");
            @else
                $('#plate').inputmask();
                $('#plate').val("For Registration");
            @endif
        });
    </script>
    @if($job->product || $job->service || $job->package || $job->promo || $job->discount)
        <script>$('#compute').val(0)</script>
        @if($job->product)
        @foreach($job->product as $key=>$product)
            <?php
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
            <script>retrieveProduct({{$product->productId}},{{$product->quantity}},{{$price}},"{{$discountString}}")</script>
        @endforeach
        @endif
        @if($job->service)
        @foreach($job->service as $key=>$service)
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
            <script>retrieveService({{$service->serviceId}},{{$price}},"{{$discountString}}")</script>
        @endforeach
        @endif
        @if($job->package)
        @foreach($job->package as $key=>$package)
            <?php
                $price = $package->package->priceRecord->where('created_at','<=',$job->created_at)->first()->price;
            ?>
            <script>retrievePackage({{$package->packageId}},{{$package->quantity}},{{$price}})</script>
        @endforeach
        @endif
        @if($job->promo)
        @foreach($job->promo as $key=>$promo)
            <?php
                $price = $promo->promo->priceRecord->where('created_at','<=',$job->created_at)->first()->price;
            ?>
            <script>retrievePromo({{$promo->promoId}},{{$promo->quantity}},{{$price}})</script>
        @endforeach
        @endif
        @if($job->discount)
            <?php
                $rate = $job->discount->discount->rateRecord->where('created_at','<=',$job->created_at)->first()->rate;
            ?>
        <script>retrieveDiscount({{$job->discount->discountId}},{{$rate}})</script>
        @endif
    @endif
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
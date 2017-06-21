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
    <div class="col-md-12">
        {!! Form::model($job , ['method' => 'patch', 'action' => ['JobController@update',$job->id]]) !!}
        @include('job.formEdit')
        {!! Form::close() !!}
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
    <script src="{{ URL::asset('js/job.js') }}"></script>
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
            $("#model").val({{$job->vehicle->modelId}});
            $(".select2").select2();
            $('#firstName').autocomplete({source: customers});
            @if($job->customer->contact[2] == '2' && old('contact')[14] == 'l')
                $('#contact').inputmask("(02) 999 9999 loc. 9999");
            @elseif($job->customer->contact == '2')
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
@stop
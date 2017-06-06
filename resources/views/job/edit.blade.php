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
    <script src="{{ URL::asset('js/job.js') }}"></script>
    <script>
        $(document).ready(function (){
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
            $("#technician").val(activeTechnicians);
            @if(old('modelId'))
                $("#model").val({{old('modelId')}});
            @endif
            $(".select2").select2();
            $('#firstName').autocomplete({source: customers});
            $('#contact').inputmask("(+639)99-9999-999");
            $('#plate').inputmask("AAA 9999");
            $('#email').inputmask("email");
            $("#mileage").inputmask({ 
                alias: "decimal",
                prefix: '',
                suffix: ' km',
                allowMinus: false,
                min: 0,
            });
            $("#compute").inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
            });
            $('#tJob').addClass('active');
        });
        $(document).on('keypress','#contact',function(){
            if($(this).val()[4]=='9'){
                $(this).inputmask("(+639)99-9999-999");
            }else if($(this).val()[4]=='2'){
                $(this).inputmask("(+639)999-9999");
            }else{
                $(this).inputmask("(+639) ERROR");
            }
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
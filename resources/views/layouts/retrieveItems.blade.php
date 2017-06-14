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
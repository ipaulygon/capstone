<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('products', 'Product Search') !!}
            <select id="products" name="productId" class="select2 form-control" style="width:100%">
                <option value=""></option>
                @foreach($products as $product)
                    @if($product->isOriginal!=null)
                        <?php $type = ($product->isOriginal=="type1" ? '- '.$util->type1 : '- '.$util->type2); ?>
                    @else
                        <?php $type = ''; ?>
                    @endif
                    <option value="{{$product->id}}">{{$product->brand}} - {{$product->name}} {{$type}} ({{$product->variance}})</option>
                @endforeach
            </select>  
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('services', 'Service Search') !!}
            <select id="services" name="serviceId" class="select2 form-control" style="width:100%">
                <option value=""></option>
                @foreach($services as $service)
                    <option value="{{$service->id}}">{{$service->name}} - {{$service->size}} ({{$service->category}})</option>
                @endforeach
            </select>  
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('packages', 'Package Search') !!}
            <select id="packages" name="packageId" class="select2 form-control" style="width:100%">
                <option value=""></option>
                @foreach($packages as $package)
                    <option value="{{$package->id}}">{{$package->name}}</option>
                @endforeach
            </select>  
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('promos', 'Promo Search') !!}
            <select id="promos" name="promoId" class="select2 form-control" style="width:100%">
                <option value=""></option>
                @foreach($promos as $promo)
                    <option value="{{$promo->id}}">{{$promo->name}}</option>
                @endforeach
            </select>  
        </div>
    </div>
    <div class="col-md-4 col-md-offset-4">
        <div class="form-group">
            {!! Form::label('discounts', 'Discount Search') !!}
            <select id="discounts" name="discountId" class="select2 form-control" style="width:100%">
                <option value=""></option>
                @foreach($discounts as $discount)
                    <option value="{{$discount->id}}">{{$discount->name}}</option>
                @endforeach
            </select>  
        </div>
    </div>
</div>
<table id="productList" class="table table-striped table-bordered responsive">
    <thead>
        <tr>
            <th width="5%" class="text-right">Quantity</th>
            <th>Item</th>
            <th width="15%" class="text-right">Unit Price</th>
            <th width="15%" class="text-right">Total Cost</th>
            <th width="5%" class="text-right">Action</th>
        </tr>
    </thead>
    <tbody></tbody>
    <tfoot id="tFoot">
    @if($util->isVat)
        <tr class="hidden">
            <th></th>
            <th>VAT Sales</th>
            <th></th>
            <th class="text-right"><input class="no-border-input" id="vatSales" type="text" value="0" readonly></strong></strong></th>
            <th></th>
        </tr>
        <tr class="hidden">
            <th></th>
            <th>VAT</th>
            <th class="text-right"><strong><input class="no-border-input" id="vatRate" type="text" value="{{$util->vat}}" readonly></strong></th>
            <th class="text-right"><strong><input class="no-border-input" id="vatStack" type="text" value="0" readonly></strong></th>
            <th></th>
        </tr>
        <tr class="hidden">
            <th></th>
            <th>VAT Exemption</th>
            <th></th>
            <th class="text-right"><strong><input class="no-border-input" id="vatExempt" type="text" value="0" readonly></strong></th>
            <th></th>
        </tr>
    @endif
    <tfoot>
</table>
<div class="col-md-12">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Estimation Details</h3>
        </div>
        <div id="body" class="box-body">
            <h4>Customer Information</h4>
            <div class="row">
                <div class="form-group col-md-4">
                    {!! Form::label('firstName', 'First Name') !!}<span>*</span>
                    {!! Form::input('text','customer[firstName]',null,[
                        'class' => 'form-control',
                        'name' => 'firstName',
                        'id' => 'firstName',
                        'placeholder'=>'First Name',
                        'maxlength'=>'100',
                        'required']) 
                    !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('middleName', 'Middle Name') !!}
                    {!! Form::input('text','customer[middleName]',null,[
                        'class' => 'form-control',
                        'name' => 'middleName',
                        'id' => 'middleName',
                        'placeholder'=>'Middle Name',
                        'maxlength'=>'100']) 
                    !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('lastName', 'Last Name') !!}<span>*</span>
                    {!! Form::input('text','customer[lastName]',null,[
                        'class' => 'form-control',
                        'name' => 'lastName',
                        'id' => 'lastName',
                        'placeholder'=>'Last Name',
                        'maxlength'=>'100',
                        'required']) 
                    !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    {!! Form::label('contact', 'Contact No.') !!}<span>*</span>
                    {!! Form::input('text','customer[contact]',null,[
                        'class' => 'form-control',
                        'name' => 'contact',
                        'id' => 'contact',
                        'placeholder'=>'Contact',
                        'required']) 
                    !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('email', 'Email') !!}
                    {!! Form::input('text','customer[email]',null,[
                        'class' => 'form-control',
                        'name' => 'email',
                        'id' => 'email',
                        'placeholder'=>'Email']) 
                    !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('address', 'Address') !!}<span>*</span>
                    {!! Form::textarea('customer[address]',null,[
                        'class' => 'form-control',
                        'name' => 'address',
                        'id' => 'address',
                        'placeholder'=>'Address',
                        'maxlength'=>'140',
                        'rows' => '1',
                        'required']) 
                    !!}
                </div>
            </div>
            <h4>Vehicle Information</h4>
            <div class="row">
                <div class="form-group col-md-4">
                    {!! Form::label('plate', 'Vehicle Plate') !!}<span>*</span>
                    {!! Form::input('text','vehicle[plate]',null,[
                        'class' => 'form-control',
                        'name' => 'plate',
                        'id' => 'plate',
                        'placeholder'=>'Vehicle Plate',
                        'required']) 
                    !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('modelId', 'Vehicle Model') !!}<span>*</span>
                    <select id="model" name="modelId" class="select2 form-control" required>
                        @foreach($models as $model)
                            <option value="{{$model->id}}">{{$model->make}} - {{$model->year}} {{$model->name}} ({{$model->transmission}})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('mileage', 'Mileage') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tachometer"></i></span>
                        {!! Form::input('text','vehicle[mileage]',null,[
                            'class' => 'form-control',
                            'name' => 'mileage',
                            'id' => 'mileage',
                            'placeholder'=>'Mileage']) 
                        !!}
                    </div>
                </div>
            </div>
            <h4>Estimate Information</h4>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('products', 'Product Search') !!}
                        <select id="products" name="productId" class="select2 form-control">
                            <option value=""></option>
                            @foreach($products as $product)
                                <option value="{{$product->id}}">{{$product->brand}} - {{$product->name}} - {{$product->isOriginal}} ({{$product->variance}})</option>
                            @endforeach
                        </select>  
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('services', 'Service Search') !!}
                        <select id="services" name="serviceId" class="select2 form-control">
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
                        <select id="packages" name="packageId" class="select2 form-control">
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
                        <select id="promos" name="promoId" class="select2 form-control">
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
                        <select id="discounts" name="discountId" class="select2 form-control">
                            <option value=""></option>
                            @foreach($discounts as $discount)
                                <option value="{{$discount->id}}">{{$discount->name}} - {{$discount->rate}} %</option>
                            @endforeach
                        </select>  
                    </div>
                </div>
            </div>
            <table id="productList" class="table table-striped responsive">
                <thead>
                    <tr>
                        <th width="5%" class="text-right">Quantity</th>
                        <th>Item</th>
                        <th width="15%" class="text-right">Unit Price</th>
                        <th width="15%" class="text-right">Total Cost</th>
                        <th width="5%">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="box-footer">
            {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
            <div class="form-inline pull-right">
                {!! Form::label('computed', 'Total Price') !!}
                <div class="input-group">
                    <span class="input-group-addon" style="border: none!important">PhP</span>
                    <strong>{!! Form::input('text','computed',0,[
                        'class' => 'form-control',
                        'id' => 'compute',
                        'style' => 'border: none!important;background: transparent!important',
                        'readonly']) 
                    !!}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
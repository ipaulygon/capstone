<div class="col-md-6">
    {{-- BOX --}}
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title nav-pills-custom">
                <ul class="nav nav-pills nav-justified" role="tablist">
                    <li role="presentation" class="active"><a href="#product" aria-controls="products" role="tab" data-toggle="tab">Products</a></li>
                    <li role="presentation"><a href="#service" aria-controls="service" role="tab" data-toggle="tab">Services</a></li>
                </ul>
            </h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="tab-content">
                {{-- PRODUCTS --}}
                <div role="tabpanel" class="tab-pane fade in active dataTable_wrapper" id="product">
                    <table id="products" class="table table-striped table-bordered responsive">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Description</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{$product->brand}} - {{$product->name}}</td>
                                <td>
                                    <li>{{$product->type}}</li>
                                    <li>{{$product->variance}}</li>
                                    @if($product->isOriginal!=null)
                                        <?php $type = ($product->isOriginal=="type1" ? $util->type1 : $util->type2); ?>
                                        <li>Part Information: {{$type}}</li>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <button id="{{$product->id}}" class="btn btn-primary btn-sm pushProduct" type="button" data-toggle="tooltip" data-placement="top" title="Add"><i class="fa fa-angle-double-right"></i></button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- SERVICES --}}
                <div role="tabpanel" class="tab-pane fade in dataTable_wrapper" id="service">
                    <table id="services" class="table table-striped table-bordered responsive">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Category</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($services as $service)
                            <tr>
                                <td>{{$service->name}} - {{$service->size}}</td>
                                <td>{{$service->category}}</td>
                                <td class="text-right">
                                    <button id="{{$service->id}}" class="btn btn-primary btn-sm pushService" type="button" data-toggle="tooltip" data-placement="top" title="Add"><i class="fa fa-angle-double-right"></i></button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Package Details</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('name', 'Package') !!}<span>*</span>
                        {!! Form::input('text','name',null,[
                            'class' => 'form-control',
                            'placeholder'=>'Name',
                            'maxlength'=>'50',
                            'required'])
                        !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('computed', 'Computed Price') !!}
                        <div class="input-group">
                            <span class="input-group-addon" style="border: none!important">PhP</span>
                            <strong>{!! Form::input('text','computed',0,[
                                'class' => 'form-control',
                                'id' => 'compute',
                                'style' => 'border: none!important;background: transparent!important',
                                'placeholder'=>'0',
                                'readonly']) 
                            !!}</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dataTable_wrapper">
                <table id="productList" class="table table-striped table-bordered responsive">
                    <thead>
                        <tr>
                            <th>Products</th>
                            <th>Quantity</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <table id="serviceList" class="table table-striped table-bordered responsive">
                    <thead>
                        <tr>
                            <th>Services</th>
                            <th>Category</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <div class="box-footer">
            {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
            <div class="form-inline pull-right">
                <div class="form-group">
                    {!! Form::label('price', 'Price') !!}<span>*</span>
                    <div class="input-group">
                        <span class="input-group-addon">PhP</span>
                        {!! Form::input('text','price',null,[
                            'class' => 'form-control',
                            'id' => 'price',
                            'placeholder'=>'Price',
                            'required']) 
                        !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

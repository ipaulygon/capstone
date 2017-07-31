<div class="col-md-12">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Purchase Order Details</h3>
        </div>
        <div class="box-body dataTable_wrapper">
            <input id="created" type="hidden" class="hidden" value="{{$created}}">
            <div class="col-md-row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('date', 'Date') !!}    
                        <strong>{!! Form::input('text','date',$date,[
                                'class' => 'form-control',
                                'id' => 'date',
                                'placeholder'=>'date',
                                'required']) 
                        !!}</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('supplierId', 'Supplier') !!}<span>*</span>
                        <select id="supp" name="supplierId" class="select2 form-control" required>
                            @foreach($suppliers as $supplier)
                                <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('products', 'Product Search') !!}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            <select id="products" name="productId" class="select2 form-control">
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
                </div>
            </div>
            <table id="productList" class="table table-striped table-bordered responsive">
                <thead>
                    <tr>
                        <th width="5%" class="text-right">Quantity</th>
                        <th>Product</th>
                        <th>Vehicle</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-right">Total Cost</th>
                        <th width="5%">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="box-footer">
            <div class="form-group">
                {!! Form::label('remarks', 'Remarks:') !!}
                {!! Form::textarea('remarks',null,[
                    'class' => 'form-control',
                    'placeholder'=>'Remarks',
                    'maxlength'=>'200',
                    'rows' => '2']) 
                !!}
            </div>
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
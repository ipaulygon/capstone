<div class="col-md-12">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Receive Delivery Details</h3>
        </div>
        <div class="box-body dataTable_wrapper">
            <div class="col-md-row">
                <div class="col-md-4">
                    <div id="append" class="form-group">
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
                        {!! Form::label('supplierId', 'Supplier') !!}
                        <select id="supp" name="supplierId" class="select2 form-control" required>
                            <option value=""></option>
                            @foreach($suppliers as $supplier)
                                <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('purchase', 'Purchase Order Search') !!}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            <select id="purchase" name="purchaseId" class="select2 form-control">
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <table id="productList" class="table table-striped table-bordered responsive">
                <thead>
                    <tr>
                        <th width="20%" class="text-right">Quantity Ordered</th>
                        <th width="30%">Product</th>
                        <th width="30%">Order Id(s)</th>
                        <th width="20%" class="text-right">Quantity Received</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="box-footer">
            {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
        </div>
    </div>
</div>
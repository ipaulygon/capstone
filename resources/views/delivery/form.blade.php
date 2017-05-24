<div class="col-md-12">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Receive Delivery Details</h3>
        </div>
        <div class="box-body dataTable_wrapper">
            <div class="col-md-row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('supplierId', 'Supplier') !!}<span>*</span>
                        <select id="supp" name="supplierId" class="select2 form-control" required>
                            <option value=""></option>
                            @foreach($suppliers as $supplier)
                                <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="append" class="form-group">
                        {!! Form::label('date', 'Date') !!}    
                        {!! Form::input('text','date',$date,[
                            'class' => 'form-control',
                            'id' => 'date',
                            'style' => 'border: none!important;background: transparent!important',
                            'readonly']) 
                        !!}
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('purchase', 'Purchase Order Search') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <select id="purchase" name="purchaseId" class="select2 form-control">
                        </select>
                    </div>
                </div>
            </div>
            <table id="productList" class="table table-striped responsive">
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
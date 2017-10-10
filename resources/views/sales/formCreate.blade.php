<div class="col-md-12">
    <ul class="nav nav-tabs" id="mainTab" role="tablist">
        <li class="active">
            <a href="#customerTab" data-toggle="tab">Customer Information</a>
        </li>
        <li>
            <a href="#salesTab" data-toggle="tab">Sales Information</a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="customerTab">    
            @include('layouts.customerCreate')
        </div>
        <div role="tabpanel" class="tab-pane fade" id="salesTab"> 
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Item List</h3> <i id="infoInventory" class="fa fa-question-circle"></i>
                </div>
                <div id="body" class="box-body">
                    @include('layouts.itemSales')
                </div>
                <div class="box-footer">
                    {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
                    <div class="form-inline pull-right">
                        {!! Form::label('computed', 'Total Price',[
                            'style' => 'font-size:18px']) !!}
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
    </div>
</div>
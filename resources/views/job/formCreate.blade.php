<div class="col-md-12">
    <button id="backNew" type="button" class="btn btn-success btn-md"><i class="fa fa-angle-double-left"></i> Back</button><br><br>
    @include('layouts.customerCreate')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Job Order Details</h3>
        </div>
        <div id="body" class="box-body">
            @include('layouts.vehicleCreate')
            <label>Item List <i id="infoInventory" class="fa fa-question-circle"></i></label>
            @include('layouts.itemList')
        </div>
        <div class="box-footer">
            <div class="form-group">
                {!! Form::label('remarks', 'Remarks:') !!}
                {!! Form::textarea('remarks',null,[
                    'class' => 'form-control',
                    'placeholder'=>'Remarks',
                    'maxlength'=>'500',
                    'rows' => '2']) 
                !!}
            </div>
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
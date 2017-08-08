<div class="col-md-12">
    @include('layouts.customerCreate')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Inspection Details</h3>
        </div>
        <div class="box-body">
            @include('layouts.vehicleCreate')
            <div id="form-box" class="panel-group" role="tab-list" aria-multiselectable="true">
            </div>
        </div>
        <div class="box-footer">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('remarks', 'Remarks') !!}
                    {!! Form::textarea('remarks',null,[
                        'class' => 'form-control',
                        'placeholder'=>'Remarks',
                        'maxlength'=>'140',
                        'rows' => '1']) 
                    !!}
                </div>
            </div>
            <div class="col-md-12">
                {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
            </div>
        </div>
    </div>
</div>
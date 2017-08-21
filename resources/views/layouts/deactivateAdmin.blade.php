{{-- Deactivate --}}
<div id="deactivateAdmin" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Deactivate</h4>
            </div>
            <div class="modal-body" style="text-align:center">
                Are you sure you want to deactivate this record?  All items included in this record will also be deactivated.
                <form action="">
                    <div class="form-group">
                        {!! Form::label('keyDeactivate', 'Admin Password:') !!}
                        {!! Form::password('keyDeactivate',null,[
                            'id' => 'keyDeactivate',
                            'name' => 'keyDeactivate',
                            'class' => 'form-control',
                            'required']) 
                        !!}
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button id="adminDeactivate" type="button" class="btn btn-danger">Deactivate</button>
            </div>
        </div>
    </div>
</div>
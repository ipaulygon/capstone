{{-- Update --}}
<div id="updateAdmin" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Update</h4>
            </div>
            <div class="modal-body" style="text-align:center">
                Are you sure you want to update this record?
                <form action="">
                    <div class="form-group">
                        {!! Form::label('keyUpdate', 'Admin Password:') !!}
                        {!! Form::password('keyUpdate',null,[
                            'id' => 'keyUpdate',
                            'name' => 'keyUpdate',
                            'class' => 'form-control',
                            'required']) 
                        !!}
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button id="adminUpdate" type="button" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</div>
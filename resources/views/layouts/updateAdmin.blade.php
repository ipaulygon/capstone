{{-- Update --}}
<div id="updateAdmin" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Update</h4>
            </div>
            <div class="modal-body hold-transition lockscreen" style="text-align:center">
                Are you sure you want to update this record?
                    <div class="lockscreen-name">{{$wholeName}}</div>
                    <div class="lockscreen-item">
                        <div class="lockscreen-image">
                            <img src="{{ URL::asset($userPicture)}}" alt="User Image">
                        </div>
                        <form class="lockscreen-credentials">
                            <div class="input-group">
                                <input id="keyUpdate" name="keyUpdate" type="password" class="form-control" placeholder="password">
                                <div class="input-group-btn">
                                    <button id="adminUpdate" type="button" class="btn btn-default"><i class="fa fa-arrow-right text-muted"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
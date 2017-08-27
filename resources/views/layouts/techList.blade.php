{{-- Technician --}}
<div id="techModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">List of Technicians</h4>
            </div>
            <div class="modal-body">
                <div class="dataTable_wrapper">
                    <table id="techList" class="table table-striped table-bordered responsive">
                        <thead>
                            <tr>
                                <th>Technician</th>
                                <th>Skills</th>
                                <th>On going tasks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($technicians as $tech)
                            <tr>
                                <td>{{$tech->firstName}} {{$tech->middleName}} {{$tech->lastName}}</td>
                                <td>
                                    @foreach($tech->skill as $skill)
                                        <li>{{$skill->category->name}}</li>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($tech->job as $task)
                                        @if($task->header->release==null && $task->header->isFinalize)
                                            <li>{{'JOB'.str_pad($task->header->id, 5, '0', STR_PAD_LEFT)}}</li>
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
            </div>
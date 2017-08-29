{{-- Inventory --}}
<div id="inventoryModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Inventory List</h4>
            </div>
            <div class="modal-body">
                <div class="dataTable_wrapper">
                    <table id="inventoryList" class="table table-striped table-bordered responsive">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-right">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inventory as $product)
                            <?php
                                if($product->isOriginal!=null){
                                    $type = ($product->isOriginal=="type1" ? $util->type1 : $util->type2);
                                }else{
                                    $type = "";
                                }
                            ?>
                            <tr>
                                <td>{{$product->brand}} - {{$product->product}} {{$type}} ({{$product->variance}})</td>
                                <td class="text-right"><input type="text" class="no-border-input inventory" id="inventory{{$product->id}}" value="{{$product->quantity}}" readonly></td>
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
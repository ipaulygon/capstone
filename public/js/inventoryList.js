var iList = $('#inventoryList').DataTable({
    'responsive': true,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});

$(document).on('click','#infoInventory',function(){
    $('#inventoryModal').modal('show');
});

$('.inventory').inputmask({ 
    alias: "integer",
    prefix: '',
    allowMinus: false,
});
var iList = $('#inventoryList').DataTable({
    'responsive': true,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
    ajax : '/item/inventory',
    "columns": [
        { "data": "brand",
            render:  function(data,type,full,meta){
                if(full.isOriginal!=null){
                    var type = (full.isOriginal=="type1" ? type1 : type2);
                }else{
                    var type = ''
                }
                return full.brand+" - "+full.product+type+' ('+full.variance+')';
            } 
        },
        { "data": "quantity",
            className: "text-right" 
        },
    ]
});

$(document).on('click','#infoInventory',function(){
    $('#inventoryModal').modal('show');
});

$('.inventory').inputmask({ 
    alias: "integer",
    prefix: '',
    allowMinus: false,
});
var receive = null;
var rList = $('#receiveList').DataTable({
    responsive: true,
    ordering: false,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});
function receiveModal(id){
    receive = id;
    rList.clear().draw();
    $.ajax({
        type: "GET",
        url: "/delivery/get/"+id,
        dataType: "JSON",
        success:function(data){
            $.each(data.delivery.detail,function(key,value){
                $.ajax({
                    type: "GET",
                    url: "/item/product/"+value.productId,
                    dataType: "JSON",
                    success:function(data){
                        if(data.product.isOriginal!=null){
                            part = (data.product.isOriginal == 'type1' ? ' - '+type1 : type2)
                        }else{
                            part = '';
                        }
                        row = rList.row.add([
                            value.quantity,
                            data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")"
                        ]).draw().node();
                        $(row).find('td').eq(0).addClass('text-right');
                    }
                });
            });
        }
    });
    $('#receiveModal').modal('show');
}
$('#receive').on('click', function (){
    $('#rec'+receive).submit();
});
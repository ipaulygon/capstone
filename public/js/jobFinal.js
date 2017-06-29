var finalize = null;
var fList = $('#finalizeList').DataTable({
    responsive: true,
    ordering: false,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});

function finalizeModal(id){
    finalize = id;
    $('#finalizeModal').modal('show');
    $.ajax({
        type: "GET",
        url: "/job/get/"+id,
        dataType: "JSON",
        success:function(data){
            fList.clear().draw();
            $.each(data.job.product,function(key,value){
                $.ajax({
                    type: "GET",
                    url: "/item/product/"+value.id,
                    dataType: "JSON",
                    success:function(data){
                        part = (data.product.isOriginal!=null ? ' - '+data.product.isOriginal : '')
                        row = fList.row.add([
                            value.quantity,
                            data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")"
                        ]).draw().node();
                        $(row).find('td').eq(0).addClass('text-right');
                    }
                });
            });
            $.each(data.job.service,function(key,value){
                $.ajax({
                    type: "GET",
                    url: "/item/service/"+value.id,
                    dataType: "JSON",
                    success:function(data){
                        row = fList.row.add([
                            '',
                            data.service.name+" - "+data.service.size+" ("+data.service.category.name+")"
                        ]).draw().node();
                        $(row).find('td').eq(0).addClass('text-right');
                    }
                });
            });
            $.each(data.job.package,function(key,value){
                $.ajax({
                    type: "GET",
                    url: "/item/package/"+value.id,
                    dataType: "JSON",
                    success:function(data){
                        row = fList.row.add([
                            value.quantity,
                            data.package.name+'<br><div id="packageItems'+data.package.id+'"></div>'
                        ]).draw().node();
                        $(row).find('td').eq(0).addClass('text-right');
                        $.each(data.package.product,function(key,value){
                            part = (value.product.isOriginal!=null ? ' - '+value.product.isOriginal : '')
                            $('#packageItems'+data.package.id).append(
                                '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.quantity+' pcs. </li>'
                            );
                        });
                        $.each(data.package.service,function(key,value){
                            $('#packageItems'+data.package.id).append(
                                '<li>'+value.service.name+" - "+value.service.size+" ("+value.service.category.name+')</li>'
                            );
                        });
                    }
                });
            });
            $.each(data.job.promo,function(key,value){
                $.ajax({
                    type: "GET",
                    url: "/item/promo/"+value.id,
                    dataType: "JSON",
                    success:function(data){
                        row = fList.row.add([
                            value.quantity,
                            data.promo.name+'<br><div id="promoItems'+data.promo.id+'"></div>'
                        ]).draw().node();
                        $(row).find('td').eq(0).addClass('text-right');
                        $.each(data.promo.product,function(key,value){
                            part = (value.product.isOriginal!=null ? ' - '+value.product.isOriginal : '')
                            $('#promoItems'+data.promo.id).append(
                                '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.quantity+' pcs. </li>'
                            );
                        });
                        $.each(data.promo.service,function(key,value){
                            $('#promoItems'+data.promo.id).append(
                                '<li>'+value.service.name+" - "+value.service.size+" ("+value.service.category.name+')</li>'
                            );
                        });
                        $('#promoItems'+data.promo.id).append(
                            '<label>Free:</label>'
                        );
                        $.each(data.promo.free_product,function(key,value){
                            part = (value.product.isOriginal!=null ? ' - '+value.product.isOriginal : '')
                            $('#promoItems'+data.promo.id).append(
                                '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.quantity+' pcs. </li>'
                            );
                        });
                        $.each(data.promo.free_service,function(key,value){
                            $('#promoItems'+data.promo.id).append(
                                '<li>'+value.service.name+" - "+value.service.size+" ("+value.service.category.name+')</li>'
                            );
                        });
                    }
                });
            });
            $.ajax({
                type: "GET",
                url: "/item/discount/"+data.job.discount.id,
                dataType: "JSON",
                success:function(data){
                    row = fList.row.add([
                        '',
                        data.discount.name+" - DISCOUNT"
                    ]).draw().node();
                    $(row).find('td').eq(0).addClass('text-right');
                }
            });
        }
    });
}
$('#finalize').on('click', function (){
    $('#fin'+finalize).submit();
});
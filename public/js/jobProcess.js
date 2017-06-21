var procList = $('#processList').DataTable({
    responsive: true,
    ordering: false,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});

function process(id){
    $.ajax({
        type: "GET",
        url: "/job/procz/"+id,
        dataType: "JSON",
        success:function(data){
            procList.clear().draw();
            $.each(data.job.product,function(key,value){
                $.ajax({
                    type: "GET",
                    url: "/item/product/"+value.id,
                    dataType: "JSON",
                    success:function(data){
                        if(value.isComplete){
                            status = '<i class="glyphicon glyphicon-ok text-success"></i> Completed';
                        }else{
                            status = '<i class="glyphicon glyphicon-remove text-danger"></i> Not Completed';
                        }
                        part = (data.product.isOriginal!=null ? ' - '+data.product.isOriginal : '')
                        row = procList.row.add([
                            '<button id="" type="button" class="btn btn-success btn-xs process" data-toggle="tooltip" data-placement="top" title="Show Details">' +
                                '<i class="glyphicon glyphicon-menu-hamburger"></i>' +
                            '</button>',
                            data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")",
                            value.quantity,
                            value.completed,
                            status,
                        ]).draw().node();
                        $(row).find('td').eq(2).addClass('text-right');
                        $(row).find('td').eq(3).addClass('text-right');
                        $(row).find('td').eq(4).addClass('text-right');
                    }
                });
            });
            $.each(data.job.service,function(key,value){
                $.ajax({
                    type: "GET",
                    url: "/item/service/"+value.id,
                    dataType: "JSON",
                    success:function(data){
                        if(value.isComplete){
                            status = '<i class="glyphicon glyphicon-ok text-success"></i> Completed';
                        }else{
                            status = '<i class="glyphicon glyphicon-remove text-danger"></i> Not Completed';
                        }
                        row = procList.row.add([
                            '<button id="" type="button" class="btn btn-success btn-xs process" data-toggle="tooltip" data-placement="top" title="Show Details">' +
                                '<i class="glyphicon glyphicon-menu-hamburger"></i>' +
                            '</button>',
                            data.service.name+" - "+data.service.size+" ("+data.service.category.name+")",
                            '',
                            '',
                            status
                        ]).draw().node();
                        $(row).find('td').eq(2).addClass('text-right');
                        $(row).find('td').eq(3).addClass('text-right');
                        $(row).find('td').eq(4).addClass('text-right');
                    }
                });
            });
            $.each(data.job.package,function(key,value){
                $.ajax({
                    type: "GET",
                    url: "/item/package/"+value.id,
                    dataType: "JSON",
                    success:function(data){
                        if(value.isComplete){
                            status = '<i class="glyphicon glyphicon-ok text-success"></i> Completed';
                        }else{
                            status = '<i class="glyphicon glyphicon-remove text-danger"></i> Not Completed';
                        }
                        row = procList.row.add([
                            '<button id="" type="button" class="btn btn-success btn-xs process" data-toggle="tooltip" data-placement="top" title="Show Details">' +
                                '<i class="glyphicon glyphicon-menu-hamburger"></i>' +
                            '</button>',
                            data.package.name+'<br><div id="packageItems'+data.package.id+'"></div>',
                            value.quantity,
                            value.completed,
                            status
                        ]).draw().node();
                        $(row).find('td').eq(2).addClass('text-right');
                        $(row).find('td').eq(3).addClass('text-right');
                        $(row).find('td').eq(4).addClass('text-right');
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
                        if(value.isComplete){
                            status = '<i class="glyphicon glyphicon-ok text-success"></i> Completed';
                        }else{
                            status = '<i class="glyphicon glyphicon-remove text-danger"></i> Not Completed';
                        }
                        row = procList.row.add([
                            '<button id="" type="button" class="btn btn-success btn-xs process" data-toggle="tooltip" data-placement="top" title="Show Details">' +
                                '<i class="glyphicon glyphicon-menu-hamburger"></i>' +
                            '</button>',
                            data.promo.name+'<br><div id="promoItems'+data.promo.id+'"></div>',
                            value.quantity,
                            value.completed,
                            status
                        ]).draw().node();
                        $(row).find('td').eq(2).addClass('text-right');
                        $(row).find('td').eq(3).addClass('text-right');
                        $(row).find('td').eq(4).addClass('text-right');
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
        }
    });
}
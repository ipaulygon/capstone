var procList = $('#processList').DataTable({
    responsive: true,
    ordering: false,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});

function process(id){
    $('#jobCarousel').carousel(2);
    $('#paymentId').val(id);
    $.ajax({
        type: "GET",
        url: "/job/get/"+id,
        dataType: "JSON",
        success:function(data){
            procList.clear().draw();
            $('.payment').remove();
            $('#totalPrice').val(data.job.total)
            balance = data.job.total-data.paid;
            $('#balance').val(balance);
            $("#balance").inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: balance,
                max: balance
            });
            if(balance==0){
                $('.addPayment').addClass('hidden');
            }else{
                $('.addPayment').removeClass('hidden');
            }
            $('#inputPayment').attr('data-qty',balance);
            $("#inputPayment").inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
                max: balance
            });
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
                            '<button id="" type="button" class="btn btn-success btn-xs process" data-toggle="collapse" data-parent="#processList" href="#prod'+data.product.id+'" title="Update Item">' +
                                '<i class="glyphicon glyphicon-menu-hamburger"></i>' +
                            '</button>',
                            data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")" +
                                '<div id="prod'+data.product.id+'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="">' +
                                '<div class="panel-body">' +
                                data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")" +
                                '</div>' +
                                '</div>',
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
                            '<button id="" type="button" class="btn btn-success btn-xs process" data-toggle="collapse" data-parent="#processList" href="#serv'+data.service.id+'" title="Update Item">' +
                                '<i class="glyphicon glyphicon-menu-hamburger"></i>' +
                            '</button>',
                            data.service.name+" - "+data.service.size+" ("+data.service.category.name+")" +
                                '<div id="serv'+data.service.id+'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="">' +
                                '<div class="panel-body">' +
                                data.service.name+" - "+data.service.size+" ("+data.service.category.name+")" +
                                '</div>' +
                                '</div>',
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
                            '<button id="" type="button" class="btn btn-success btn-xs process" data-toggle="collapse" data-parent="#processList" href="#pack'+data.package.id+'" title="Update Item">' +
                                '<i class="glyphicon glyphicon-menu-hamburger"></i>' +
                            '</button>',
                            data.package.name+'<br><div id="packageItems'+data.package.id+'"></div>' +
                                '<div id="pack'+data.package.id+'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="">' +
                                '<div class="panel-body">' +
                                data.package.name +
                                '</div>' +
                                '</div>',
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
                            '<button id="" type="button" class="btn btn-success btn-xs process" data-toggle="collapse" data-parent="#processList" href="#promo'+data.promo.id+'" title="Update Item">' +
                                '<i class="glyphicon glyphicon-menu-hamburger"></i>' +
                            '</button>',
                            data.promo.name+'<br><div id="promoItems'+data.promo.id+'"></div>' +
                                '<div id="promo'+data.promo.id+'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="">' +
                                '<div class="panel-body">' +
                                data.promo.name +
                                '</div>' +
                                '</div>',
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
            $.each(data.job.payment,function(key,value){
                $('#paymentList').append(
                    '<div class="col-md-12 paymentList">' +
                    '<input class="payment prices col-md-6" value="'+value.paid+'" style="border:none!important;background: transparent!important" readonly>' +
                    '<label>'+value.created_at+'</label>' +
                    '</div>'
                );
            });
            $(".prices").inputmask({ 
                alias: "currency",
                prefix: 'PhP ',
                allowMinus: false,
                autoGroup: true,
            });
        }
    });
}

$(document).on('click','#savePayment', function(){
    balance = $('#balance').val().replace(',','');
    payment = $('#inputPayment').val().replace(',','');
    id = $('#paymentId').val();
    if(payment!="0.00"){
        balance = eval(balance+"-"+payment);
        $('#balance').val(balance);
        $("#balance").inputmask({ 
            alias: "currency",
            prefix: '',
            allowMinus: false,
            autoGroup: true,
            min: balance,
            max: balance
        });
        $('#inputPayment').attr('data-qty',balance);
        $("#inputPayment").inputmask({ 
            alias: "currency",
            prefix: '',
            allowMinus: false,
            autoGroup: true,
            min: 0,
            max: balance
        });
        $(this).popover('hide');
        $('#inputPayment').val('0.00');
        $.ajax({
            type: "POST",
            url: "job/pay",
            data: {id: id,payment: payment},
            success:function(data){
                $('#notif').append(
                    '<div id="alert" class="alert alert-success alert-dismissible fade in">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    '<h4><i class="icon fa fa-check"></i> Success!</h4>' +
                    data.message +
                    '</div>'
                );
                $('#paymentList').append(
                    '<div class="col-md-12 paymentList">' +
                    '<input class="payment prices col-md-6" value="'+data.payment+'" style="border:none!important;background: transparent!important" readonly>' +
                    '<label>'+data.job.updated_at+'</label>' +
                    '</div>'
                );
                $(".prices").inputmask({ 
                    alias: "currency",
                    prefix: 'PhP ',
                    allowMinus: false,
                    autoGroup: true,
                });
            }
        });
    }else{
        $('#notif').append(
            '<div id="alert" class="alert alert-danger alert-dismissible fade in">' +
            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
            '<h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>' +
            'Invalid Payment' +
            '</div>'
        );
    }
    setTimeout(function (){
        $('#alert').alert('close');
    },3000);
});

$(document).on('keyup', '#inputPayment' ,function (){
    if(Number($(this).val().replace(',','')) > Number($(this).attr('data-qty'))){
        $(this).popover({
            trigger: 'manual',
            content: function(){
                var content = "Oops! Your input exceeds the price to be paid. The max value will be set.";
                return content;
            },
            placement: function(){
                var placement = 'top';
                return placement;
            },
            template: '<div class="popover alert-danger" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
        });
        $(this).popover('show');
    }else{
        $(this).popover('hide');
    }
});
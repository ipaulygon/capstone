var procList = $('#processList').DataTable({
    "responsive": true,
    "ordering": false,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});
var payList = $('#paymentList').DataTable({
    "responsive": true,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});
$("#totalPrice").inputmask({ 
    alias: "currency",
    prefix: '',
    allowMinus: false,
    autoGroup: true,
    min: 0,
});
$('#cardNo').inputmask('9999 9999 9999 9999');
$('#cardExp').inputmask('99/99');
$('#cardSec').inputmask('999',{
    showMaskOnHover: false,
    showMaskOnFocus: false,
});
$(document).on('keyup','#cardNo',function(){
    pop = $(this);
    if($(this).val().charAt(0)=='4' || $(this).val().charAt(0)=='5'){
        pop.popover('hide');
        if($(this).val().charAt(0)=='4'){
            $('#visaPic').removeClass('hidden');
            $('#mcPic').addClass('hidden');
        }else{
            $('#visaPic').addClass('hidden');
            $('#mcPic').removeClass('hidden');
        }
    }else{
        $('#visaPic').addClass('hidden');
        $('#mcPic').addClass('hidden');
        $(this).popover({
            trigger: 'manual',
            content: function(){
                var content = "Oops! Only Visa and Master Card our available. Try starting with 4 or 5";
                return content;
            },
            placement: function(){
                var placement = 'top';
                return placement;
            },
            template: '<div class="popover alert-danger" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
        });
        $(this).popover('show');
        setTimeout(function(){
            pop.popover('hide');
        },2000);
    }
});
var lessPayment = null;
function process(id){
    $('#jobCarousel').carousel(2);
    $('#processId').val(id);
    if(userType==2){
        $('.addPayment').addClass('hidden');
        $('#refundPayment').addClass('hidden');
        $('#listPayments').addClass('hidden');
        $('#listPaymentButton').addClass('hidden');
    }
    $.ajax({
        type: 'GET',
        url: '/job/check/'+id,
        dataType: 'JSON',
        success:function(data){
            $('.processTechs').remove();
            if(data.job.isVoid){
                $('#processUpdate').addClass('hidden');
            }else{
                $('#processUpdate').removeClass('hidden');
            }
            $('#processUpdate').attr('onclick','updateAdmin('+id+',"job")');
            $('#processStart').text(data.job.start);
            $('#processEnd').text(data.job.release);
            $('#processPlate').text(data.job.vehicle.plate);
            transmission = (data.job.vehicle.isManual ? 'MT' : 'AT');
            $('#processModel').text(data.job.vehicle.model.make.name+" - "+data.job.vehicle.model.name+" - "+transmission);
            $('#processMileage').text(data.job.vehicle.mileage);
            $('#processCustomer').text(data.job.customer.firstName+" "+data.job.customer.middleName+" "+data.job.customer.lastName);
            $.each(data.job.technician,function(key,value){
                $('#processTechs').append('<li class="processTechs">'+value.technician.firstName+' '+value.technician.lastName+'</li>');
            });
            $('#processRemarksValue').text(data.job.remarks);
            $('#processRemarks').val(data.job.remarks);
        }
    })
    $.ajax({
        type: "GET",
        url: "/job/get/"+id,
        dataType: "JSON",
        success:function(data){
            var count = 0;
            var completed = 0;
            procList.clear().draw();
            payList.clear().draw();
            $('.payment').remove();
            $('#totalPrice').val(data.job.total)
            balance = data.job.total-data.paid+data.refund;
            $('#balance').val(balance);
            $("#balance").inputmask({ 
                alias: "currency",
                prefix: 'PhP ',
                allowMinus: true,
                autoGroup: true,
                min: balance,
                max: balance
            });
            if(balance<=0 && userType==1){
                $('.addPayment').addClass('hidden');
                if(balance<0 && userType==1){
                    $('#refundPayment').removeClass('hidden');
                }else{
                    $('#refundPayment').addClass('hidden');
                }
            }else if(balance>0 && userType==1){
                $('.addPayment').removeClass('hidden');
                $('#refundPayment').addClass('hidden');
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
                count += value.quantity;
                completed += value.completed;
                $.ajax({
                    type: "GET",
                    url: "/item/product/"+value.productId,
                    dataType: "JSON",
                    success:function(data){
                        maxInput = (value.quantity>data.product.inventory.quantity ? data.product.inventory.quantity:value.quantity)
                        if(value.isComplete){
                            status = '<i class="glyphicon glyphicon-ok text-success"></i> Completed';
                        }else{
                            status = '<i class="glyphicon glyphicon-remove text-danger"></i> Not Completed';
                        }
                        if(data.product.isOriginal!=null){
                            part = (data.product.isOriginal == 'type1' ? ' - '+type1 : type2)
                        }else{
                            part = '';
                        }
                        row = procList.row.add([
                            data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")",
                            value.quantity,
                            '<input class="qty form-control text-right" name="prodQty[]" data-tresh="'+maxInput+'" id="prod'+value.id+'" type="text" value="'+value.completed+'">',
                            '<p id="prodStatus'+value.id+'">'+status+'</>',
                            '<button data-id="'+value.id+'" data-item="'+data.product.id+'" type="button" class="procProduct btn btn-primary btn-sm"><i class="glyphicon glyphicon-refresh"></i> Update</button>'
                        ]).draw().node();
                        procList.row($(row)).invalidate().draw();
                        dataList = procList.row($(row)).data();
                        dataList.push(data);
                        procList.row($(row)).data(dataList);
                        $(row).find('td').eq(1).addClass('text-right');
                        $(row).find('td').eq(2).addClass('text-right');
                        $(row).find('td').eq(3).addClass('text-right');
                        $(row).find('td').eq(4).addClass('text-right');
                        $('#prod'+value.id).inputmask({ 
                            alias: "integer",
                            prefix: '',
                            allowMinus: false,
                            min: 0,
                            max: maxInput,
                        });
                    }
                });
            });
            $.each(data.job.service,function(key,value){
                count++;
                completed += value.isComplete;
                $.ajax({
                    type: "GET",
                    url: "/item/service/"+value.serviceId,
                    dataType: "JSON",
                    success:function(data){
                        if(value.isComplete){
                            status = '<i class="glyphicon glyphicon-ok text-success"></i> Completed';
                            ifStatus = "checked";
                        }else{
                            status = '<i class="glyphicon glyphicon-remove text-danger"></i> Not Completed';
                            ifStatus = "";
                        }
                        row = procList.row.add([
                            data.service.name+" - "+data.service.size+" ("+data.service.category.name+")",
                            '',
                            '<input class="serviceCheck" type="checkbox" '+ifStatus+'>' +
                            '<input type="hidden" class="serviceDone" id="serv'+value.id+'" name="serviceDone[]" value="'+value.isComplete+'">',
                            '<p id="servStatus'+value.id+'">'+status+'</>',
                            '<button data-id="'+value.id+'" data-item="'+data.service.id+'" type="button" class="procService btn btn-primary btn-sm"><i class="glyphicon glyphicon-refresh"></i> Update</button>'
                        ]).draw().node();
                        $(row).find('td').eq(1).addClass('text-right');
                        $(row).find('td').eq(2).addClass('text-right');
                        $(row).find('td').eq(3).addClass('text-right');
                        $(row).find('td').eq(4).addClass('text-right');
                    }
                }); 
            });
            $.each(data.job.package,function(key,value){
                count += value.quantity;
                completed += value.completed;
                $.ajax({
                    type: "GET",
                    url: "/item/package/"+value.packageId,
                    dataType: "JSON",
                    success:function(data){
                        maxInput = value.quantity;
                        if(value.isComplete){
                            status = '<i class="glyphicon glyphicon-ok text-success"></i> Completed';
                        }else{
                            status = '<i class="glyphicon glyphicon-remove text-danger"></i> Not Completed';
                        }
                        row = procList.row.add([
                            data.package.name+'<br><div id="packageItems'+data.package.id+'"></div>',
                            value.quantity,
                            '<input class="qty form-control text-right" name="packQty[]" data-tresh="'+maxInput+'" id="pack'+value.id+'" type="text" value="'+value.completed+'">',
                            '<p id="packStatus'+value.id+'">'+status+'</>',
                            '<button data-id="'+value.id+'" data-item="'+data.package.id+'" type="button" class="procPackage btn btn-primary btn-sm"><i class="glyphicon glyphicon-refresh"></i> Update</button>'
                        ]).draw().node();
                        $(row).find('td').eq(1).addClass('text-right');
                        $(row).find('td').eq(2).addClass('text-right');
                        $(row).find('td').eq(3).addClass('text-right');
                        $(row).find('td').eq(4).addClass('text-right');
                        $('#pack'+value.id).inputmask({ 
                            alias: "integer",
                            prefix: '',
                            allowMinus: false,
                            min: 0,
                            max: maxInput,
                        });
                        $.each(data.package.product,function(key,value){
                            if(value.product.isOriginal!=null){
                                part = (value.product.isOriginal == 'type1' ? ' - '+type1 : type2)
                            }else{
                                part = '';
                            }   
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
                count += value.quantity;
                completed += value.completed;
                $.ajax({
                    type: "GET",
                    url: "/item/promo/"+value.promoId,
                    dataType: "JSON",
                    success:function(data){
                        maxInput = value.quantity;
                        if(value.isComplete){
                            status = '<i class="glyphicon glyphicon-ok text-success"></i> Completed';
                        }else{
                            status = '<i class="glyphicon glyphicon-remove text-danger"></i> Not Completed';
                        }
                        row = procList.row.add([
                            data.promo.name+'<br><div id="promoItems'+data.promo.id+'"></div>',
                            value.quantity,
                            '<input class="qty form-control text-right" name="promoQty[]" data-tresh="'+maxInput+'" id="promo'+value.id+'" type="text" value="'+value.completed+'">',
                            '<p id="promoStatus'+value.id+'">'+status+'</>',
                            '<button data-id="'+value.id+'" data-item="'+data.promo.id+'" type="button" class="procPromo btn btn-primary btn-sm"><i class="glyphicon glyphicon-refresh"></i> Update</button>'
                        ]).draw().node();
                        $(row).find('td').eq(1).addClass('text-right');
                        $(row).find('td').eq(2).addClass('text-right');
                        $(row).find('td').eq(3).addClass('text-right');
                        $(row).find('td').eq(4).addClass('text-right');
                        $('#promo'+value.id).inputmask({ 
                            alias: "integer",
                            prefix: '',
                            allowMinus: false,
                            min: 0,
                            max: maxInput,
                        });
                        $.each(data.promo.product,function(key,value){
                            if(value.product.isOriginal!=null){
                                part = (value.product.isOriginal == 'type1' ? ' - '+type1 : type2)
                            }else{
                                part = '';
                            }   
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
                            if(value.product.isOriginal!=null){
                                part = (value.product.isOriginal == 'type1' ? ' - '+type1 : type2)
                            }else{
                                part = '';
                            }   
                            $('#promoItems'+data.promo.id).append(
                                '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.freeQuantity+' pcs. </li>'
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
                method = (value.isCredit ? "Credit Card" : "Cash");
                row = payList.row.add([
                    '<input class="form-control prices no-border-input" value="'+value.paid+'" id="payment'+value.id+'" readonly>',
                    method,
                    value.created_at,
                    '<button type="button" data-id="'+value.id+'" class="btn btn-warning btn-sm edit-payment" data-toggle="tooltip" data-placement="top" title="Edit Payment">'+
                    '<i class="glyphicon glyphicon-edit"></i>'+
                    '</button>' +
                    '<a href="job/receipt/pdf/'+value.id+'" target="_blank" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Generate Receipt">'+
                    '<i class="glyphicon glyphicon-file"></i>'+
                    '</a>'
                ]).draw().node();
                $(row).find('td').eq(1).addClass('text-right');
                $(row).find('td').eq(2).addClass('text-right');
                $(row).find('td').eq(3).addClass('text-right');
            });
            $.each(data.job.refund,function(key,value){
                row = payList.row.add([
                    '<input class="form-control prices no-border-input" value="'+value.refund+'" id="refund'+value.id+'" readonly>',
                    '',
                    value.created_at,
                    'Refund'
                ]).draw().node();
                $(row).find('td').eq(1).addClass('text-right');
                $(row).find('td').eq(2).addClass('text-right');
                $(row).find('td').eq(3).addClass('text-right');
            });
            $(".prices").inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
            });
            var percentage = Math.round((completed/count)*100);
            $('#progress-bar').text(percentage+'%');
            $('#progress-bar').attr('aria-valuenow',percentage+'');
            $('#progress-bar').css('width',percentage+'%');
        }
    });
}

$(document).on('keyup','#processRemarks',function(){
    jobId = $('#processId').val();
    remarks = $(this).val();
    $.ajax({
       type: 'POST',
       url: '/job/remarks',
       data: {jobId:jobId,remarks:remarks},
       success: function(data){
            $('#processRemarksValue').text(data.remarks);
       } 
    });
});

$(document).on('click','.edit-payment', function(){
    id = $(this).attr('data-id');
    lessPayment = $('#payment'+id).val();
    $('#payment'+id).removeClass('no-border-input');
    $('#payment'+id).attr('readonly',false);
    $(this).removeClass('edit-payment');
    $(this).removeClass('btn-warning');
    $(this).addClass('update-payment');
    $(this).addClass('btn-success');
    $(this).attr('title','Update Payment');
    $(this).children().remove();
    $(this).append('<i class="glyphicon glyphicon-ok"></i>');
    $('#payment'+id).inputmask({
        alias: "currency",
        prefix: '',
        allowMinus: false,
        autoGroup: true,
        min: 0,
        max: $('#inputPayment').attr('data-qty')+lessPayment
    });
    $('.edit-payment').addClass('disabled');
    $('.addPayment').addClass('hidden');
});

$(document).on('click','.update-payment', function(){
    id = $(this).attr('data-id');
    jobId = $('#processId').val();
    addPayment = $('#payment'+id).val();
    $('.edit-payment').removeClass('disabled');
    $('#payment'+id).addClass('no-border-input');
    $('#payment'+id).attr('readonly',true);
    $(this).removeClass('update-payment');
    $(this).removeClass('btn-success');
    $(this).addClass('edit-payment');
    $(this).addClass('btn-warning');
    $(this).attr('title','Edit Payment');
    $(this).children().remove();
    $(this).append('<i class="glyphicon glyphicon-edit"></i>');
    $.ajax({
        type: 'POST',
        url: 'job/updatePay',
        data: {id:id,jobId:jobId,less:lessPayment,add:addPayment},
        success: function(data){
            $('#notif').append(
                '<div id="alert" class="alert alert-success alert-dismissible fade in">' +
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                '<h4><i class="icon fa fa-check"></i> Success!</h4>' +
                data.message +
                '</div>'
            );
            balance = data.job.total-data.job.paid;
            $('#balance').val(balance);
            $("#balance").inputmask({ 
                alias: "currency",
                prefix: 'PhP ',
                allowMinus: true,
                autoGroup: true,
                min: balance,
                max: balance
            });
            if(balance<=0 && userType==1){
                $('.addPayment').addClass('hidden');
                if(balance<0 && userType==1){
                    $('#refundPayment').removeClass('hidden');
                }else{
                    $('#refundPayment').addClass('hidden');
                }
            }else if(balance>0 && userType==1){
                $('.addPayment').removeClass('hidden');
                $('#refundPayment').addClass('hidden');
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
        }
    });
    lessPayment = addPayment;
    checkJob(jobId);
    setTimeout(function (){
        $('#alert').alert('close');
    },2000);
    if(userType==1){
        $('.addPayment').removeClass('hidden');
    }
});

$(document).on('click','#savePayment', function(){
    balance = $('#balance').val().replace(/,/g,'');
    balance = balance.replace('PhP ','');
    payment = $('#inputPayment').val().replace(/,/g,'');
    method = $('#paymentMethod').val();
    cardName = $('#cardName').val();
    cardNumber = $('#cardNo').val();
    cardExp = $('#cardExp').val();
    cardSec = $('#cardSec').val();
    id = $('#processId').val();
    passed = false;
    if(method==1){
        passed = ((cardName!=null ||cardName!='') && (cardNumber!=null || cardNumber!='') && (cardNumber[0]=='4' || cardNumber[0]=='5') && (cardExp!=null || cardExp!='') && (cardSec!=null || cardSec!='') ? true : false);
    }else{
        passed = true;
    }
    if(payment!="0.00" && passed && Number(payment)<=Number(balance)){
        balance = eval(balance+"-"+payment);
        if(balance==0 && userType==1){
            $('.addPayment').addClass('hidden');
            if(balance<0 && userType==1){
                $('#refundPayment').removeClass('hidden');
            }else{
                $('#refundPayment').addClass('hidden');
            }
        }else if(balance>0 && userType==1){
            $('.addPayment').removeClass('hidden');
            $('#refundPayment').addClass('hidden');
        }
        $('#balance').val(balance);
        $("#balance").inputmask({ 
            alias: "currency",
            prefix: 'PhP ',
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
            data: {id: id,payment: payment,method: method,cardName: cardName,cardNumber: cardNumber,cardExp: cardExp,cardSec: cardSec},
            success:function(data){
                $('#notif').append(
                    '<div id="alert" class="alert alert-success alert-dismissible fade in">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    '<h4><i class="icon fa fa-check"></i> Success!</h4>' +
                    data.message +
                    '</div>'
                );
                method = (method==1 ? "Credit Card" : "Cash");
                row = payList.row.add([
                    '<input class="form-control prices no-border-input" value="'+payment+'" id="payment'+data.payment.id+'" readonly>',
                    method,
                    data.payment.created_at,
                    '<button type="button" data-id="'+data.payment.id+'" class="btn btn-warning btn-sm edit-payment" data-toggle="tooltip" data-placement="top" title="Edit Payment">'+
                    '<i class="glyphicon glyphicon-edit"></i>'+
                    '</button>' +
                    '<a href="job/receipt/pdf/'+data.payment.id+'" target="_blank" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Generate Receipt">'+
                    '<i class="glyphicon glyphicon-file"></i>'+
                    '</a>'
                ]).draw().node();
                $(row).find('td').eq(1).addClass('text-right');
                $(row).find('td').eq(2).addClass('text-right');
                $(row).find('td').eq(3).addClass('text-right');
                $(".prices").inputmask({ 
                    alias: "currency",
                    prefix: '',
                    allowMinus: false,
                    autoGroup: true,
                });
            }
        });
        checkJob(id);
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
    },2000);
});

$(document).on('click','#refundPayment',function(){
    balance = $('#balance').val().replace(/,/g,'');
    refund = balance.replace('-PhP ','');
    id = $('#processId').val();
    balance = 0;
    if(balance==0 && userType==1){
        $('.addPayment').addClass('hidden');
        if(balance<0 && userType==1){
            $('#refundPayment').removeClass('hidden');
        }else{
            $('#refundPayment').addClass('hidden');
        }
    }else if(balance>0 && userType==1){
        $('.addPayment').removeClass('hidden');
        $('#refundPayment').addClass('hidden');
    }
    $('#balance').val(balance);
    $("#balance").inputmask({ 
        alias: "currency",
        prefix: 'PhP ',
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
        url: "job/refund",
        data: {id: id,refund: refund},
        success:function(data){
            $('#notif').append(
                '<div id="alert" class="alert alert-success alert-dismissible fade in">' +
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                '<h4><i class="icon fa fa-check"></i> Success!</h4>' +
                data.message +
                '</div>'
            );
            row = payList.row.add([
                '<input class="form-control prices no-border-input" value="'+refund+'" id="refund'+data.refund.id+'" readonly>',
                method,
                data.refund.created_at,
                '',
                'Refund'
            ]).draw().node();
            $(row).find('td').eq(1).addClass('text-right');
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            $(".prices").inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
            });
        }
    });
    checkJob(id);
});

$(document).on('keyup', '#inputPayment' ,function (){
    if(Number($(this).val().replace(/,/g,'')) > Number($(this).attr('data-qty'))){
        $(this).tooltip({
            trigger: 'manual',
            title: function(){
                var content = "Oops! Your input exceeds the price to be paid. The max value will be set.";
                return content;
            },
            container: 'body',
            placement: function(){
                var placement = 'left';
                return placement;
            },
            template: '<div class="tooltip alert-danger" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
        });
        $(this).tooltip('show');
    }else{
        $(this).tooltip('hide');
    }
});

$(document).on('focus','#inputPayment',function(){
    $(this).popover({
        trigger: 'manual',
        content: function(){
            var content = '<button type="button" id="cashP" class="btn btn-primary btn-block">Cash</button><button type="button" id="creditP" class="btn btn-primary btn-block">Credit Card</button>';
            return content;
        },
        container: 'body',
        html: true,
        placement: function(){
            var placement = 'top';
            return placement;
        },
        template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
        title: function(){
            var title = 'Choose mode of Payment:';
            return title;
        }
    });
    $(this).popover('show');
});

$(document).on('focusout','#inputPayment',function(){
    $(this).popover('hide');
    $(this).tooltip('hide');
});

$(document).on('click','#cashP',function(){
    $('#creditCard').addClass('hidden');
    $('#paymentMethod').val(0);
});

$(document).on('click','#creditP',function(){
    $('#creditCard').removeClass('hidden');
    $('#paymentMethod').val(1);
});

$(document).on('change','.serviceCheck',function(){
    if($(this).prop('checked')){
        $(this).parent().find('.serviceDone').val(1);
    }else{
        $(this).parent().find('.serviceDone').val(0);
    }
})

$(document).on('keyup','.qty',function(){
    if(Number($(this).val()) > Number($(this).attr('data-tresh'))){
        $(this).tooltip({
            trigger: 'manual',
            title: function(){
                var content = "Oops! Your input exceeds the availability of the item. The max value will be set.";
                return content;
            },
            container: 'body',
            placement: function(){
                var placement = 'left';
                return placement;
            },
            template: '<div class="tooltip alert-danger" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
        });
        $(this).tooltip('show');
    }else{
        $(this).tooltip('hide');
    }
})

$(document).on('focusout','.qty',function(){
    $(this).popover('hide');
    $(this).tooltip('hide');
})

$(document).on('click','.procProduct',function(){
    jobId = $('#processId').val();
    detailId = $(this).attr('data-id');
    productId = $(this).attr('data-item');
    detailQty = $('#prod'+detailId).val();
    $.ajax({
        type: 'POST',
        url: '/job/product',
        data: {detailId:detailId,productId:productId,detailQty:detailQty},
        success: function(data){
            if(data.error==0){
                $('#notif').append(
                    '<div id="alert" class="alert alert-success alert-dismissible fade in">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    '<h4><i class="icon fa fa-check"></i> Success!</h4>' +
                    data.message +
                    '</div>'
                );
                status = (data.completed ? '<i class="glyphicon glyphicon-ok text-success"></i> Completed' : '<i class="glyphicon glyphicon-remove text-danger"></i> Not Completed');
                $('#prodStatus'+detailId).html(status);
                iList.ajax.reload();
            }else{
                $('#notif').append(
                    '<div id="alert" class="alert alert-danger alert-dismissible fade in">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    '<h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>' +
                    data.message +
                    '</div>'
                );
            }
        }
    });
    checkJob(jobId);
    setTimeout(function (){
        $('#alert').alert('close');
    },2000);
})

$(document).on('click','.procService',function(){
    jobId = $('#processId').val();
    detailId = $(this).attr('data-id');
    serviceId = $(this).attr('data-item');
    detailDone = $('#serv'+detailId).val();
    $.ajax({
        type: 'POST',
        url: '/job/service',
        data: {detailId:detailId,serviceId:serviceId,detailDone:detailDone},
        success: function(data){
            $('#notif').append(
                '<div id="alert" class="alert alert-success alert-dismissible fade in">' +
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                '<h4><i class="icon fa fa-check"></i> Success!</h4>' +
                data.message +
                '</div>'
            );
            status = (data.completed==1 ? '<i class="glyphicon glyphicon-ok text-success"></i> Completed' : '<i class="glyphicon glyphicon-remove text-danger"></i> Not Completed');
            $('#servStatus'+detailId).html(status);
        }
    });
    checkJob(jobId);
    setTimeout(function (){
        $('#alert').alert('close');
    },2000);
})

$(document).on('click','.procPackage',function(){
    jobId = $('#processId').val();
    detailId = $(this).attr('data-id');
    packageId = $(this).attr('data-item');
    detailQty = $('#pack'+detailId).val();
    $.ajax({
        type: 'POST',
        url: '/job/package',
        data: {detailId:detailId,packageId:packageId,detailQty:detailQty},
        success: function(data){
            if(data.error==0){
                $('#notif').append(
                    '<div id="alert" class="alert alert-success alert-dismissible fade in">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    '<h4><i class="icon fa fa-check"></i> Success!</h4>' +
                    data.message +
                    '</div>'
                );
                status = (data.completed==1 ? '<i class="glyphicon glyphicon-ok text-success"></i> Completed' : '<i class="glyphicon glyphicon-remove text-danger"></i> Not Completed');
                $('#packStatus'+detailId).html(status);
                iList.ajax.reload();
            }else{
                $('#notif').append(
                    '<div id="alert" class="alert alert-danger alert-dismissible fade in">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    '<h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>' +
                    data.message +
                    '</div>'
                );
            }
        }
    });
    checkJob(jobId);
    setTimeout(function (){
        $('#alert').alert('close');
    },2000);
})

$(document).on('click','.procPromo',function(){
    jobId = $('#processId').val();
    detailId = $(this).attr('data-id');
    promoId = $(this).attr('data-item');
    detailQty = $('#promo'+detailId).val();
    $.ajax({
        type: 'POST',
        url: '/job/promo',
        data: {detailId:detailId,promoId:promoId,detailQty:detailQty},
        success: function(data){
            if(data.error==0){
                $('#notif').append(
                    '<div id="alert" class="alert alert-success alert-dismissible fade in">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    '<h4><i class="icon fa fa-check"></i> Success!</h4>' +
                    data.message +
                    '</div>'
                );
                status = (data.completed==1 ? '<i class="glyphicon glyphicon-ok text-success"></i> Completed' : '<i class="glyphicon glyphicon-remove text-danger"></i> Not Completed');
                $('#promoStatus'+detailId).html(status);
                iList.ajax.reload();
            }else{
                $('#notif').append(
                    '<div id="alert" class="alert alert-danger alert-dismissible fade in">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    '<h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>' +
                    data.message +
                    '</div>'
                );
            }
        }
    });
    checkJob(jobId);
    setTimeout(function (){
        $('#alert').alert('close');
    },2000);
})

function checkJob(id){
    $.ajax({
        type: 'POST',
        url: '/job/final',
        data: {id:id},
        success: function(data){
            $.each(data.jobs,function(key,value){
                if(value.release!=null && value.isComplete && value.total==value.paid){
                    colors = '#6f5499';
                }else if(value.isComplete && value.total==value.paid){
                    colors = "#00a65a";
                }else if(value.isComplete && value.total!=value.paid){
                    colors = "#00a65a";
                }else if(!value.isComplete && value.total==value.paid){
                    colors = "#00c0ef";
                }else if(!value.isComplete && value.isFinalize && value.total!=value.paid){
                    colors = "#f39c12";
                }else{
                    colors = "#3c8dbc";
                }
                strId = String("00000" + value.id).slice(-5)
                var events = {
                    id: strId,
                    title: value.plate,
                    start: value.start,
                    end: value.release,
                    color: colors
                };
                $("#calendar").fullCalendar('removeEventSources');
                $('#calendar').fullCalendar('renderEvent', events, true);
            });
            var percentage = Math.round((data.completed/data.count)*100);
            $('#progress-bar').text(percentage+'%');
            $('#progress-bar').attr('aria-valuenow',percentage+'');
            $('#progress-bar').css('width',percentage+'%');
        }
    });
}
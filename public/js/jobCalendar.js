$('#jobCarousel').carousel({
    interval: false,
    keyboard: false
});
var procView = $('#processView').DataTable({
    "responsive": true,
    "ordering": false,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});
var payView = $('#paymentView').DataTable({
    "responsive": true,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});
var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
var day = null;
var datePick = moment(new Date()).format("YYYY-MM-DD");
$('#start').val(datePick);
$('#calendar').fullCalendar({
    contentHeight: 600,
    customButtons: {
        started: {
            text: 'Started',
        },
        finalized: {
            text: 'Finalized',
        },
        paid: {
            text: 'Paid',
        },
        done: {
            text: 'Done',
        },
        released: {
            text: 'Released',
        },
    },
    dayClick: function(date,jsEvent,view){
        if(!$(this).hasClass('fc-future')){
            datePick = date.format();
            picked = new Date(datePick);
            today = new Date();
            sub = Math.abs(today.getTime() - picked.getTime());
            sub = Math.ceil(sub/(1000*3600*24));
            if(sub-1 <= backlog){
                if(day!=null){
                    day.removeClass('day-click');
                }
                $(this).addClass('day-click');
                day = $(this);
                $('#start').val(datePick);
                $('#dateSelected').text(datePick);
                $('#dateSelectedView').text(monthNames[picked.getMonth()]+" "+picked.getDate()+", "+picked.getFullYear());
                $('#detailBox').addClass('hidden');
            }else{
                ping(this);
            }
        }else{
            ping(this);
        }
    },
    defaultView: 'listDay',
    eventClick: function(event,jsEvent,view){
        clickEvent(event.id);
    },
    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'started,finalized,paid,done,released'
    },
    viewRender: function(currentView){
        maxDate = moment().add(2,'weeks');
        if (maxDate >= currentView.start && maxDate <= currentView.end) {
			$(".fc-next-button").prop('disabled', true); 
			$(".fc-next-button").addClass('fc-state-disabled'); 
		} else {
			$(".fc-next-button").removeClass('fc-state-disabled'); 
			$(".fc-next-button").prop('disabled', false); 
        }
    }
});
function ping(comp){
    $(comp).tooltip({
        container: 'body',
        placement: 'auto',
        title: 'Cannot be selected'
    });
    $(comp).tooltip('show');
    var dayClicked = $(comp);
    setTimeout(function(){
        dayClicked.tooltip('hide');
    },1000);
}
$('.fc-started-button').addClass('btn-primary');
$('.fc-started-button').removeClass('fc-button fc-state-default fc-state-hover fc-corner-left');
$('.fc-finalized-button').addClass('btn-warning');
$('.fc-finalized-button').removeClass('fc-button fc-state-default fc-state-hover');
$('.fc-paid-button').addClass('btn-info');
$('.fc-paid-button').removeClass('fc-button fc-state-default fc-state-hover');
$('.fc-done-button').addClass('btn-success');
$('.fc-done-button').removeClass('fc-button fc-state-default fc-state-hover fc-corner-right');
$('.fc-released-button').addClass('btn-success');
$('.fc-released-button').css('background-color','#6f5499!important');
$('.fc-released-button').removeClass('fc-button fc-state-default fc-state-hover fc-corner-right');

function clickEvent(id){
    var finalize = null;
    var completed = null;
    $.ajax({
        type: "GET",
        url: "/job/check/"+id,
        dataType: "JSON",
        success:function(data){
            var jobId = String("00000" + data.job.id).slice(-5);
            $('#detailId').text('JOB'+jobId);
            $('#detailRack').text(data.job.rack.name);
            $('#detailStart').text(data.job.start);
            $('#detailEnd').text(data.job.end);
            $('#detailPlate').text(data.job.vehicle.plate);
            transmission = (data.job.vehicle.isManual ? 'MT' : 'AT');
            $('#detailModel').text(data.job.vehicle.model.make.name+" - "+data.job.vehicle.model.name+" - "+transmission);
            $('#detailMileage').text(data.job.vehicle.mileage);
            $('#detailCustomer').text(data.job.customer.firstName+" "+data.job.customer.middleName+" "+data.job.customer.lastName);
            $('.detailTechs').remove();
            $.each(data.job.technician,function(key,value){
                $('#detailTechs').append('<li class="detailTechs">'+value.technician.firstName+' '+value.technician.lastName+'</li>');
            });
            if(data.job.isVoid){
                $('#detailEstimate').addClass('hidden');
            }else{
                $('#detailEstimate').removeClass('hidden');
            }
            if(data.job.isFinalize){
                $('#detailPDF').removeClass('hidden');
                $('#detailProcess').removeClass('hidden');
                $('#detailUpdate').addClass('hidden');
                $('#detailFinalize').addClass('hidden');
                if(data.job.isComplete && data.job.total==data.job.paid){
                    $('#detailRelease').removeClass('hidden');
                }
                if(data.job.release!=null){
                    $('#detailRelease').addClass('hidden');
                    $('#detailProcess').addClass('hidden');
                    $('#detailEstimate').addClass('hidden');
                }
            }else{
                $('#detailPDF').addClass('hidden');
                $('#detailProcess').addClass('hidden');
                $('#detailUpdate').removeClass('hidden');
                $('#detailFinalize').removeClass('hidden');
            }
        }
    });
    $('#detailBox').removeClass('hidden');
    $('#detailUpdate').attr("href","/job/"+id+"/edit");
    $('#detailEstimate').attr("data-id",id);
    $('#detailEstimate').attr("data-type",'estimate');
    $('#detailPDF').attr("data-id",id);
    $('#detailPDF').attr("data-type",'job');
    $('#detailFinalize').attr("data-id",id);
    $('#detailProcess').attr("data-id",id);
    $('#detailRelease').attr("data-id",id);
    $('#detailView').attr("data-id",id);
}

$('#detailEstimate').click(function(){
    signatureModal($(this).attr('data-id'),$(this).attr('data-type'));
});

$('#detailPDF').click(function(){
    signatureModal($(this).attr('data-id'),$(this).attr('data-type'));
});

$('#detailFinalize').click(function(){
    finalizeModal($(this).attr('data-id'));
});

$('#detailProcess').click(function(){
    process($(this).attr('data-id'));
});
$('#detailRelease').click(function(){
    releaseVehicle($(this).attr('data-id'));
});
$('#detailView').click(function(){
    view($(this).attr('data-id'));
});

function hoverEvent(id,element){
    $.ajax({
        type: "GET",
        url: "/job/check/"+id,
        dataType: "JSON",
        success:function(data){
            element.popover({
                container: 'body',
                trigger: 'manual',
                content: function(){
                    var content = '<label>Job Id:</label> <span id="popId"></span><br>' +
                                '<div class="col-md-12">' +
                                    '<div class="col-md-6 pull-left">' +
                                        '<label>Start:</label> <span id="popStart"></span>' +
                                    '</div>' +
                                    '<div class="col-md-6 pull-right">' +
                                        '<label>End:</label> <span id="popEnd"></span>' +
                                    '</div>' +
                                '</div>' +
                                '<label>Vehicle:</label><br>' +
                                '<ul>' +
                                    '<li>Plate: <span id="popPlate"></span></li>' +
                                    '<li>Model: <span id="popModel"></span></li>' +
                                    '<li>Mileage: <span id="popMileage"></span></li>' +
                                '</ul>' +
                                '<label>Customer:</label> <span id="popCustomer"></span><br>';
                    return content;
                },
                html: true,
                placement: function(){
                    var placement = 'right';
                    return placement;
                },
                template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
                title: function(){
                    var title = 'Job Order Details:';
                    return title;
                }
            });
            element.popover('show');
            var jobId = String("00000" + data.job.id).slice(-5);
            $('#popId').text('JOB'+jobId);
            $('#popStart').text(data.job.start);
            $('#popEnd').text(data.job.end);
            $('#popPlate').text(data.job.vehicle.plate);
            $('#popModel').text(data.job.vehicle.model.make.name+" - "+data.job.vehicle.model.name+" ("+data.job.vehicle.model.transmission+")");
            $('#popMileage').text(data.job.vehicle.mileage);
            $('#popCustomer').text(data.job.customer.firstName+" "+data.job.customer.middleName+" "+data.job.customer.lastName);
        }
    });
}

$(document).on('click','#viewTable',function(){
    $('#viewTable').addClass('hidden');
    $('#viewMonth').addClass('hidden');
    $('#viewWeek').addClass('hidden');
    $('#viewDay').addClass('hidden');
    $('#detailBox').addClass('hidden');
    $('#viewCalendar').removeClass('hidden');
});

$(document).on('click','#viewCalendar',function(){
    $('#viewCalendar').addClass('hidden');
    $('#viewMonth').removeClass('hidden');
    $('#viewWeek').removeClass('hidden');
    $('#viewDay').removeClass('hidden');
    $('#viewTable').removeClass('hidden');
});

$(document).on('click','#viewMonth',function(){
    $('#calendar').fullCalendar('changeView', 'month',datePick);
    $('#viewMonth').addClass('disabled');
    $('#viewWeek').removeClass('disabled');
    $('#viewDay').removeClass('disabled');
});

$(document).on('click','#viewWeek',function(){
    $('#calendar').fullCalendar('changeView', 'agendaWeek',datePick);
    $('#viewWeek').addClass('disabled');
    $('#viewMonth').removeClass('disabled');
    $('#viewDay').removeClass('disabled');
});

$(document).on('click','#viewDay',function(){
    $('#calendar').fullCalendar('changeView', 'listDay',datePick);
    $('#viewDay').addClass('disabled');
    $('#viewMonth').removeClass('disabled');
    $('#viewWeek').removeClass('disabled');
});

$(document).on('click','#addNew',function(){
    $('#jobCarousel').carousel(1);
});

$(document).on('click','#backNew',function(){
    // $('#jobCarousel').carousel(0);
    window.location.href = "/job";
});


$(document).on('click','#backProcess',function(){
    window.location.href = "/job";
    // $('#detailBox').addClass('hidden');
    // $('#jobCarousel').carousel(0);
});

function view(id){
    $.ajax({
        type: 'GET',
        url: '/job/check/'+id,
        dataType: 'JSON',
        success:function(data){
            $('.viewTechs').remove();
            var jobId = String("00000" + data.job.id).slice(-5);
            $('#viewId').text('JOB'+jobId);
            $('#viewRack').text(data.job.rack.name);
            $('#viewStart').text(data.job.start);
            $('#viewStart').text(data.job.start);
            $('#viewEnd').text(data.job.release);
            $('#viewPlate').text(data.job.vehicle.plate);
            transmission = (data.job.vehicle.isManual ? 'MT' : 'AT');
            $('#viewModel').text(data.job.vehicle.model.make.name+" - "+data.job.vehicle.model.name+" - "+transmission);
            $('#viewMileage').text(data.job.vehicle.mileage);
            $('#viewCustomer').text(data.job.customer.firstName+" "+data.job.customer.middleName+" "+data.job.customer.lastName);
            $.each(data.job.technician,function(key,value){
                $('#viewTechs').append('<li class="viewTechs">'+value.technician.firstName+' '+value.technician.lastName+'</li>');
            });
        }
    })
    $.ajax({
        type: "GET",
        url: "/job/get/"+id,
        dataType: "JSON",
        success:function(data){
            var count = 0;
            var completed = 0;
            procView.clear().draw();
            payView.clear().draw();
            $('.paymentView').remove();
            $('#viewPrice').val(data.job.total)
            balance = data.job.total-data.paid+data.refund;
            $('#viewBalance').val(balance);
            $("#viewBalance").inputmask({ 
                alias: "currency",
                prefix: 'PhP ',
                allowMinus: false,
                autoGroup: true,
                min: balance,
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
                        row = procView.row.add([
                            data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")",
                            value.quantity,
                            value.completed,
                            '<p id="prodStatus'+value.id+'">'+status+'</>',
                        ]).draw().node();
                        $(row).find('td').eq(1).addClass('text-right');
                        $(row).find('td').eq(2).addClass('text-right');
                        $(row).find('td').eq(3).addClass('text-right');
                        $(row).find('td').eq(4).addClass('text-right');
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
                        row = procView.row.add([
                            data.service.name+" - "+data.service.size+" ("+data.service.category.name+")",
                            '',
                            '',
                            '<p id="servStatus'+value.id+'">'+status+'</>',
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
                        if(value.isComplete){
                            status = '<i class="glyphicon glyphicon-ok text-success"></i> Completed';
                        }else{
                            status = '<i class="glyphicon glyphicon-remove text-danger"></i> Not Completed';
                        }
                        row = procView.row.add([
                            data.package.name+'<br><div id="packageItemsView'+data.package.id+'"></div>',
                            value.quantity,
                            value.completed,
                            '<p id="packStatus'+value.id+'">'+status+'</>',
                        ]).draw().node();
                        $(row).find('td').eq(1).addClass('text-right');
                        $(row).find('td').eq(2).addClass('text-right');
                        $(row).find('td').eq(3).addClass('text-right');
                        $(row).find('td').eq(4).addClass('text-right');
                        $.each(data.package.product,function(key,value){
                            if(value.product.isOriginal!=null){
                                part = (value.product.isOriginal == 'type1' ? ' - '+type1 : type2)
                            }else{
                                part = '';
                            }   
                            $('#packageItemsView'+data.package.id).append(
                                '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.quantity+' pcs. </li>'
                            );
                        });
                        $.each(data.package.service,function(key,value){
                            $('#packageItemsView'+data.package.id).append(
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
                        if(value.isComplete){
                            status = '<i class="glyphicon glyphicon-ok text-success"></i> Completed';
                        }else{
                            status = '<i class="glyphicon glyphicon-remove text-danger"></i> Not Completed';
                        }
                        row = procView.row.add([
                            data.promo.name+'<br><div id="promoItemsView'+data.promo.id+'"></div>',
                            value.quantity,
                            value.completed,
                            '<p id="promoStatus'+value.id+'">'+status+'</>',
                        ]).draw().node();
                        $(row).find('td').eq(1).addClass('text-right');
                        $(row).find('td').eq(2).addClass('text-right');
                        $(row).find('td').eq(3).addClass('text-right');
                        $(row).find('td').eq(4).addClass('text-right');
                        $.each(data.promo.product,function(key,value){
                            if(value.product.isOriginal!=null){
                                part = (value.product.isOriginal == 'type1' ? ' - '+type1 : type2)
                            }else{
                                part = '';
                            }   
                            $('#promoItemsView'+data.promo.id).append(
                                '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.quantity+' pcs. </li>'
                            );
                        });
                        $.each(data.promo.service,function(key,value){
                            $('#promoItemsView'+data.promo.id).append(
                                '<li>'+value.service.name+" - "+value.service.size+" ("+value.service.category.name+')</li>'
                            );
                        });
                        $('#promoItemsView'+data.promo.id).append(
                            '<label>Free:</label>'
                        );
                        $.each(data.promo.free_product,function(key,value){
                            if(value.product.isOriginal!=null){
                                part = (value.product.isOriginal == 'type1' ? ' - '+type1 : type2)
                            }else{
                                part = '';
                            }   
                            $('#promoItemsView'+data.promo.id).append(
                                '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.quantity+' pcs. </li>'
                            );
                        });
                        $.each(data.promo.free_service,function(key,value){
                            $('#promoItemsView'+data.promo.id).append(
                                '<li>'+value.service.name+" - "+value.service.size+" ("+value.service.category.name+')</li>'
                            );
                        });
                    }
                });
            });
            $.each(data.job.payment,function(key,value){
                method = (value.isCredit ? "Credit Card" : "Cash");
                row = payView.row.add([
                    '<input class="pricesView no-border-input" value="'+value.paid+'" readonly>',
                    method,
                    value.created_at,
                    '<a href="job/receipt/pdf/'+value.id+'" target="_blank" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Generate Receipt">'+
                    '<i class="glyphicon glyphicon-file"></i>'+
                    '</a>'
                ]).draw().node();
                $(row).find('td').eq(1).addClass('text-right');
                $(row).find('td').eq(2).addClass('text-right');
                $(row).find('td').eq(3).addClass('text-right');
            });
            $.each(data.job.refund,function(key,value){
                row = payView.row.add([
                    '<input class="pricesView no-border-input" value="'+value.refund+'" id="refund'+value.id+'" readonly>',
                    '',
                    value.created_at,
                    'Refund'
                ]).draw().node();
                $(row).find('td').eq(0).addClass('text-right');
                $(row).find('td').eq(2).addClass('text-right');
                $(row).find('td').eq(3).addClass('text-right');
            });
            $(".pricesView").inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
            });
            var percentage = Math.round((completed/count)*100);
            $('#progress-view').text(percentage+'%');
            $('#progress-view').attr('aria-valuenow',percentage+'');
            $('#progress-view').css('width',percentage+'%');
        }
    });
    $('#viewModal').modal('show');
}
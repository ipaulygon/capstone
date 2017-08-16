$('#jobCarousel').carousel({
    interval: false,
    keyboard: false
});

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
        }
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
                $('#detailBox').addClass('hidden');
            }else{
                ping(this);
            }
        }else{
            ping(this);
        }
    },
    defaultView: 'month',
    eventClick: function(event,jsEvent,view){
        clickEvent(event.id);
    },
    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'started,finalized,paid,done'
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

function clickEvent(id){
    $('.detailTechs').remove();
    var finalize = null;
    var completed = null;
    $.ajax({
        type: "GET",
        url: "/job/check/"+id,
        dataType: "JSON",
        success:function(data){
            var jobId = String("00000" + data.job.id).slice(-5);
            $('#detailId').text('JOB'+jobId);
            $('#detailStart').text(data.job.start);
            $('#detailEnd').text(data.job.end);
            $('#detailPlate').text(data.job.vehicle.plate);
            transmission = (data.job.vehicle.isManual ? 'MT' : 'AT');
            $('#detailModel').text(data.job.vehicle.model.make.name+" - "+data.job.vehicle.model.year+" "+data.job.vehicle.model.name+" - "+transmission);
            $('#detailMileage').text(data.job.vehicle.mileage);
            $('#detailCustomer').text(data.job.customer.firstName+" "+data.job.customer.middleName+" "+data.job.customer.lastName);
            $.each(data.job.technician,function(key,value){
                $('#detailTechs').append('<li class="detailTechs">'+value.technician.firstName+' '+value.technician.lastName+'</li>');
            });
            if(data.job.isFinalize){
                $('#detailPDF').removeClass('hidden');
                $('#detailUpdate').addClass('hidden');
                $('#detailFinalize').addClass('hidden');
                if(data.job.isCompleted){
                    $('#detailProcess').addClass('hidden');
                }else{
                    $('#detailProcess').removeClass('hidden');
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
    $('#detailEstimate').attr("href","/estimate/pdf/"+id);
    $('#detailPDF').attr("href","/job/pdf/"+id);
    $('#detailFinalize').attr("onclick","finalizeModal("+id+")");
    $('#detailProcess').attr("onclick","process("+id+")");
}

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
            $('#popModel').text(data.job.vehicle.model.make.name+" - "+data.job.vehicle.model.year+" "+data.job.vehicle.model.name+" ("+data.job.vehicle.model.transmission+")");
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
    $('#jobCarousel').carousel(0);
});


$(document).on('click','#backProcess',function(){
    $('#jobCarousel').carousel(0);
});
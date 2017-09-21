$('#email').inputmask("email");
$('#bday').datepicker({
    format: 'mm/dd/yyyy',
    startDate: '-100y',
    endDate: '-18y',
    autoclose: true,
    todayHighlight: true,
});
$('#bday').inputmask("99/99/9999");
$('#bday').on('show', function(e){
    console.debug('show', e.date, $(this).data('stickyDate'));
    if ( e.date ) {
         $(this).data('stickyDate', e.date);
    }
    else {
         $(this).data('stickyDate', null);
    }
});
$('#bday').on('hide', function(e){
    console.debug('hide', e.date, $(this).data('stickyDate'));
    var stickyDate = $(this).data('stickyDate');
    if ( !e.date && stickyDate ) {
        console.debug('restore stickyDate', stickyDate);
        $(this).datepicker('setDate', stickyDate);
        $(this).data('stickyDate', null);
    }
});
$(document).on('focus','#contact',function(){
    $(this).popover({
        trigger: 'manual',
        content: function(){
            var content = '<button type="button" id="cp" class="btn btn-primary col-md-12">Mobile No.</button><button type="button" id="tp" class="btn btn-primary col-md-12">Telephone No.</button>';
            return content;
        },
        html: true,
        placement: function(){
            var placement = 'top';
            return placement;
        },
        template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
        title: function(){
            var title = 'Choose a format:';
            return title;
        }
    });
    $(this).popover('show');
});
$(document).on('focusout','#contact',function(){
    $(this).popover('hide');
});
$(document).on('click','#cp',function(){
    $('#contact').val('');
    $('#contact').inputmask("+63 999 9999 999");
});
$(document).on('click','#tp',function(){
    $('#contact').val('');
    $('#contact').inputmask("(02) 999 9999");
});
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
            reader.onload = function (e) {
                $('#tech-pic')
                .attr('src', e.target.result)
                .width(180);
            };
        reader.readAsDataURL(input.files[0]);
    }
}
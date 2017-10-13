$('#quantity').inputmask({
    alias: 'integer',
    prefix: '',
    allowMinus: false,
    min: 1,
    max: 1
});

$(document).on('change','#products',function(){
    $('#quantity').inputmask({
        alias: 'integer',
        prefix: '',
        allowMinus: false,
        min: 1,
        max: $('option:selected',this).attr('data-max')
    });
});
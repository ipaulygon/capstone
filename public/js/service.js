$("#year").inputmask({ 
    alias: "integer",
    prefix: '',
    allowMinus: false,
    autoGroup: true,
    min: 0,
    max: wYear
});
$("#day").inputmask({ 
    alias: "integer",
    prefix: '',
    allowMinus: false,
    autoGroup: true,
    min: 0,
    max: wDay
});
$("#month").inputmask({ 
    alias: "integer",
    prefix: '',
    allowMinus: false,
    autoGroup: true,
    min: 0,
    max: wMonth
});

$('.warranty').change(function(){
    if($(this).prop('checked')){
        $('#year').attr('readonly',false);
        $('#month').attr('readonly',false);
        $('#day').attr('readonly',false);
        $('#year').val(wYear);
        $('#month').val(wMonth);
        $('#day').val(wDay);
        $('#isWarranty').val(1);
    }else{
        $('#year').attr('readonly',true);
        $('#month').attr('readonly',true);
        $('#day').attr('readonly',true);
        $('#year').val(0);
        $('#month').val(0);
        $('#day').val(0);
        $('#isWarranty').val(0);
    }
});

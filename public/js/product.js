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

function changeType(id){
    $.ajax({
        type: "GET",
        url: "/product/type/"+id,
        dataType: "JSON",
        success:function(data){
            $('#pb').empty();
            $('#pv').empty();
            $.each(data.brands,function(index, value){
                $('#pb').append($("<option></option>")
                    .attr("value", value.brand.id).text(value.brand.name));
            });
            $.each(data.variances,function(index, value){
                $('#pv').append($("<option></option>")
                    .attr("value", value.variance.id).text(value.variance.name));
            });
            $('#pb').select2();
            $('#pv').select2();
            if(data.type.category=='category1'){
                $('#part').removeClass('hidden');
                $("#isOriginal[value=type1]").prop('checked',true);
            }else{
                $("#isOriginal[value=type1]").prop('checked',false);
                $("#isOriginal[value=type2]").prop('checked',false);
                $('#vehicle').val('');
                $('#vehicle').select2();
                $('#part').addClass('hidden');
            }
        }
    });
}
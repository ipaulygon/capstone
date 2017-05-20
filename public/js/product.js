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
            if(data.type.category=='Parts'){
                $('#part').removeClass('hidden');
            }else{
                $('.square-blue').iCheck('uncheck');
                $('#vehicle').val('');
                $('#vehicle').select2();
                $('#part').addClass('hidden');
            }
        }
    });
}
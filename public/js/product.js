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
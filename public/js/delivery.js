var pList = $('#productList').DataTable({
    'responsive': true,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});

function rowFinder(row){
    if($(row).closest('table').hasClass("collapsed")) {
        var child = $(row).parents("tr.child");
        row = $(child).prevAll(".parent");
    } else {
        row = $(row).parents('tr');
    }
    return row;
}

$(document).on('change', '#supp', function(){
    $('.hidden.order').remove();
    $('#purchase option').remove();
    pList.clear().draw();
    $.ajax({
        type: "GET",
        url: "/delivery/header/"+this.value,
        dataType: "JSON",
        success:function(data){
            $('#purchase').append('<option value=""></option>');
            $.each(data.purchases,function(key, value){
                $('#purchase').append('<option value="'+value.id+'">'+value.id+'</option>')
            });
            $('#purchase').val('');
            $("#purchase").select2();
        }
    });
});

$(document).on('click','.select2-results__option',function(){
    id = $(this).text();
    $('#purchase option[value="'+id+'"]').attr('disabled',false);
    $('#order'+id).remove();
    $.ajax({
        type: "GET",
        url: "/delivery/detail/"+id,
        dataType: "JSON",
        success:function(data){
            $.each(data.products,function(key,value){
                stack = eval($('#qo'+value.productId).val()+"-"+value.quantity);
                if(value.quantity==0)
                if(stack==0){
                    var row = rowFinder($('#qo'+value.productId));
                    pList.row(row).remove().draw();
                }
                $('#qo'+value.productId).val(stack);
                $('#rowDesc'+value.productId+' #list'+value.purchaseId).remove();
                $('#qr'+value.productId).inputmask({ 
                    alias: "integer",
                    prefix: '',
                    allowMinus: false,
                    min: 0,
                    max: stack,
                });
            });
        }
    });
    $('#purchase').val('');
    $("#purchase").select2();
});

$(document).on('change','#purchase',function(){
    $('#purchase option[value="'+this.value+'"]').attr('disabled',true);
    $('#append').append('<input class="hidden order" id="order'+this.value+'" name="order[]" value="'+this.value+'">');
    $.ajax({
        type: "GET",
        url: "/delivery/detail/"+this.value,
        dataType: "JSON",
        success:function(data){
            $.each(data.products,function(key,value){
                if(value.quantity!=value.delivered){
                    if(value.productId==$('#id'+value.productId).val()){
                        stack = eval($('#qo'+value.productId).val()+"+"+value.quantity);
                        $('#qo'+value.productId).val(stack);
                        $('#rowDesc'+value.productId).append('<li>'+value.purchaseId+' x '+value.quantity+" pcs."+'</li>');
                        $('#qr'+value.productId).inputmask({ 
                            alias: "integer",
                            prefix: '',
                            allowMinus: false,
                            min: 1,
                            max: stack,
                        });
                    }else{
                        part = (value.isOriginal!=null ? ' - '+value.isOriginal : '')
                        row = pList.row.add([
                            '<input type="hidden" id="id'+value.productId+'" name="product[]" value="'+value.productId+'"><strong><input class="qo" id="qo'+value.productId+'" style="border: none!important;background: transparent!important" type="text" value="'+value.quantity+'" readonly> pcs.</strong>',
                            value.brand+" - "+value.product+part+" ("+value.variance+")",
                            '<li id="list'+value.purchaseId+'">'+value.purchaseId+' x '+value.quantity+" pcs."+'</li>',
                            '<input type="text" class="form-control qr" id="qr'+value.productId+'" name="qty[]" required>'
                        ]).draw().node();
                        $(row).find('td').eq(0).addClass('text-right');
                        $(row).find('td').eq(2).attr('id','rowDesc'+value.productId);
                        $(row).find('td').eq(3).addClass('text-right');
                        $('#qo'+value.productId).inputmask({ 
                            alias: "integer",
                            prefix: '',
                            allowMinus: false,
                        });
                        $('#qr'+value.productId).inputmask({ 
                            alias: "integer",
                            prefix: '',
                            allowMinus: false,
                            min: 0,
                            max: value.quantity,
                        });
                    }
                }
            });
        }
    });
    $('#purchase').val('');
    $("#purchase").select2();
});
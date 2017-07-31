var pList = $('#productList').DataTable({
    'responsive': true,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});
$('#date').datepicker({
    format: 'mm/dd/yyyy',
    endDate: new Date,
    startDate: '-7d',
    autoclose: true,
    todayHighlight: true,
});
$('#date').inputmask("99/99/9999");
$('#date').on('show', function(e){
    console.debug('show', e.date, $(this).data('stickyDate'));
    if ( e.date ) {
         $(this).data('stickyDate', e.date);
    }
    else {
         $(this).data('stickyDate', null);
    }
});
$('#date').on('hide', function(e){
    console.debug('hide', e.date, $(this).data('stickyDate'));
    var stickyDate = $(this).data('stickyDate');
    if ( !e.date && stickyDate ) {
        console.debug('restore stickyDate', stickyDate);
        $(this).datepicker('setDate', stickyDate);
        $(this).data('stickyDate', null);
    }
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

$(document).on('keyup', '.qr' ,function (){
    if(Number($(this).val()) > Number($(this).attr('data-qty'))){
        $(this).popover({
            trigger: 'manual',
            content: function(){
                var content = "Oops! Your input exceeds the corresponding number of orders. The max value will be set.";
                return content;
            },
            placement: function(){
                var placement = 'top';
                return placement;
            },
            template: '<div class="popover alert-danger" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
        });
        $(this).popover('show');
        pop = $(this);
        setTimeout(function(){
            pop.popover('hide');
        },2000);
    }
});

$(document).on('focusout','.qr',function(){
    $(this).popover('hide');
});

$(document).on('change', '#supp', function(){
    $('.hidden.order').remove();
    $('#purchase option').remove();
    pList.clear().draw();
    $.ajax({
        type: "GET",
        url: "/delivery/header/"+this.value,
        dataType: "JSON",
        success:function(data){
            var start = new Date();
            $('#purchase').append('<option value=""></option>');
            $.each(data.purchases,function(key, value){
                $('#purchase').append('<option value="'+value.id+'">'+value.id+'</option>');
                if(start > new Date(value.dateMake)){
                    start = value.dateMake;
                } 
            });
            start = start.split('-');
            start = start[1]+"/"+start[2]+"/"+start[0];
            $('#date').datepicker('remove');
            $('#date').datepicker({
                format: 'mm/dd/yyyy',
                endDate: new Date,
                startDate: start+'',
                autoclose: false,
                todayHighlight: true,
            });
            $('#date').datepicker('update');
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
                balance = value.quantity - value.delivered;
                stack = eval($('#qo'+value.productId).val()+"-"+balance);
                if(stack==0){
                    var row = rowFinder($('#qo'+value.productId));
                    pList.row(row).remove().draw();
                }else{
                    $('#qo'+value.productId).val(stack);
                    $('#rowDesc'+value.productId+' #list'+value.purchaseId).remove();
                    $('#qr'+value.product.id).attr('data-qty',stack);
                    $('#qr'+value.productId).inputmask({ 
                        alias: "integer",
                        prefix: '',
                        allowMinus: false,
                        min: 0,
                        max: stack,
                    });
                }
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
                        balance = value.quantity - value.delivered;
                        stack = eval($('#qo'+value.productId).val()+"+"+balance);
                        $('#qo'+value.productId).val(stack);
                        $('#rowDesc'+value.productId).append('<li id="list'+value.purchaseId+'">'+value.purchaseId+' x '+balance+" pcs."+'</li>');
                        $('#qr'+value.product.id).attr('data-qty',stack);
                        $('#qr'+value.productId).inputmask({ 
                            alias: "integer",
                            prefix: '',
                            allowMinus: false,
                            min: 1,
                            max: stack,
                        });
                    }else{
                        if(value.isOriginal!=null){
                            part = (value.isOriginal == 'type1' ? ' - '+type1 : type2)
                        }else{
                            part = '';
                        }
                        balance = value.quantity - value.delivered;
                        row = pList.row.add([
                            '<input type="hidden" id="id'+value.productId+'" name="product[]" value="'+value.productId+'"><strong><input class="qo" id="qo'+value.productId+'" style="border: none!important;background: transparent!important" type="text" value="'+balance+'" readonly> pcs.</strong>',
                            value.brand+" - "+value.product+part+" ("+value.variance+")",
                            '<li id="list'+value.purchaseId+'">'+value.purchaseId+' x '+balance+" pcs."+'</li>',
                            '<input type="text" data-qty="'+value.quantity+'" class="form-control qr" id="qr'+value.productId+'" name="qty[]" required>'
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
                            max: balance,
                        });
                    }
                }
            });
        }
    });
    $('#purchase').val('');
    $("#purchase").select2();
});
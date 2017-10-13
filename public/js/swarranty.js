var sList = $('#salesList').DataTable({
    'responsive': true,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});

var sProduct = $('#salesProduct').DataTable({
    'responsive': true,
    "ordering" : false,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});

var sPackage = $('#salesPackage').DataTable({
    'responsive': true,
    "ordering" : false,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});
var sPromo = $('#salesPromo').DataTable({
    'responsive': true,
    "ordering" : false,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});
// DATATABLE
function rowFinder(row){
    if($(row).closest('table').hasClass("collapsed")) {
        var child = $(row).parents("tr.child");
        row = $(child).prevAll(".parent");
    } else {
        row = $(row).parents('tr');
    }
    return row;
}

$(document).on('click','#salesObtain',function(){
    id = $(this).attr('data-id');
    $('#salesId').val(id);
	$.ajax({
        type: "GET",
        url: "/warranty/sales/"+id,
        dataType: "JSON",
        success:function(data){
            $('#salesList').DataTable().clear().draw();
            $('#salesProduct').DataTable().clear().draw();
            $('#salesPackage').DataTable().clear().draw();
            $('#salesPromo').DataTable().clear().draw();
            //product
            $.each(data.sales.product,function(key,value){
                salesProductList(value);
            });
            //package
            $.each(data.sales.package,function(key,value){
                salesPackageList(value);
            });
            //promo
            $.each(data.sales.promo,function(key,value){
                salesPromoList(value);
            });
        }
    });
	$('#salesModal').modal('show');
});

function salesProductList(value) {
    $.ajax({
        type: "GET",
        url: "/item/product/"+value.productId,
        dataType: "JSON",
        success: function(data) {
            if(data.product.isOriginal!=null){
                part = (data.product.isOriginal == 'type1' ? ' - '+type1 : type2)
            }else{
                part = '';
            }
            productString = data.product.brand.name+' - '+data.product.name+part+' ('+data.product.variance.name+') x '+value.quantity+' pcs.';
            row = sProduct.row.add([
                productString,
                '<button data-id="'+value.id+'" class="btn btn-primary btn-xs pushSalesProduct" type="button" data-toggle="tooltip" data-placement="top" title="Add/Remove"><i class="fa fa-angle-double-right"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(1).addClass('text-right');
        }
    });
}

$(document).on('click','.pushSalesProduct',function(){
    $(this).removeClass('btn-primary pushSalesProduct');
    $(this).addClass('btn-danger pullSalesProduct');
    $(this).find('i').attr('class','fa fa-angle-double-left');
    salesProduct($(this).attr('data-id'));
});

$(document).on('click','.pullSalesProduct',function(){
    $(this).removeClass('btn-danger pullSalesProduct');
    $(this).addClass('btn-primary pushSalesProduct');
    $(this).find('i').attr('class','fa fa-angle-double-right');
    $('.sproduct'+$(this).attr('data-id')).each(function(){
        var row = rowFinder(this);
        sList.row(row).remove().draw();
    })
});

function salesProduct(id) {
    $.ajax({
        type: "GET",
        url: '/warranty/sales/product/'+id,
        dataType: 'JSON',
        success: function(value){
            $.ajax({
                type: "GET",
                url: "/item/product/warranty/"+value.productId,
                dataType: "JSON",
                success: function(data) {
                    if(data.product.isOriginal!=null){
                        part = (data.product.isOriginal == 'type1' ? ' - '+type1 : type2)
                    }else{
                        part = '';
                    }
                    productString = data.product.brand.name+' - '+data.product.name+part+' ('+data.product.variance.name+')';
                    row = sList.row.add([
                        value.quantity,
                        productString,
                        '',
                        '<input type="hidden" name="product[]" value="'+data.product.id+'"><input type="hidden" name="salesProduct[]" value="'+value.id+'"><input type="text" class="form-control qty text-right" id="qty" name="productQty[]" value="0" required>'
                    ]).draw().node();
                    $(row).find('.qty').inputmask({
                        alias: "integer",
                        prefix: '',
                        allowMinus: false,
                        min: 0,
                        max: value.quantity,
                    });
                    $(row).find('td').eq(0).addClass('text-right');
                    $(row).find('td').eq(0).addClass('sproduct'+value.id);
                    $(row).find('td').eq(3).addClass('text-right');
                }
            });
        }
    });
}

function salesPackageList(value){
    $.ajax({
        type: "GET",
        url: "/item/package/"+value.packageId,
        dataType: "JSON",
        success:function(data){
            row = sPackage.row.add([
                data.package.name,
                '<button data-id="'+value.id+'" class="btn btn-primary btn-xs pushSalesPackage" type="button" data-toggle="tooltip" data-placement="top" title="Add/Remove"><i class="fa fa-angle-double-right"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(1).addClass('text-right');
        }
    });
}

$(document).on('click','.pushSalesPackage',function(){
    $(this).removeClass('btn-primary pushSalesPackage');
    $(this).addClass('btn-danger pullSalesPackage');
    $(this).find('i').attr('class','fa fa-angle-double-left');
    salesPackage($(this).attr('data-id'));
});

$(document).on('click','.pullSalesPackage',function(){
    $(this).removeClass('btn-danger pullSalesPackage');
    $(this).addClass('btn-primary pushSalesPackage');
    $(this).find('i').attr('class','fa fa-angle-double-right');
    $('.spackage'+$(this).attr('data-id')).each(function(){
        var row = rowFinder(this);
        sList.row(row).remove().draw();
    })
});

function salesPackage(id){
    $.ajax({
        type: "GET",
        url: "/warranty/sales/package/"+id,
        dataType: "JSON",
        success:function(value){
            $.ajax({
                type: "GET",
                url: "/item/package/"+value.packageId,
                dataType: "JSON",
                success:function(data){
                    $.each(data.package.product,function(key1,value1){
                        if(value1.product.isOriginal!=null){
                            part = (value1.product.isOriginal == 'type1' ? ' - '+type1 : type2)
                        }else{
                            part = '';
                        }
                        row = sList.row.add([
                            value1.quantity,
                            value1.product.brand.name+' - '+value1.product.name+part+' ('+value1.product.variance.name+')',
                            data.package.name,
                            '<input type="hidden" name="packageProduct[]" value="'+value1.product.id+'"><input type="hidden" name="salesPackage[]" value="'+value.id+'"><input type="text" class="form-control qty text-right" id="qty" name="packageProductQty[]" value="0" required>'
                        ]).draw().node();
                        $(row).find('.qty').inputmask({ 
                            alias: "integer",
                            prefix: '',
                            allowMinus: false,
                            min: 0,
                            max: value.quantity*value1.quantity,
                        });
                        $(row).find('td').eq(0).addClass('text-right');
                        $(row).find('td').eq(0).addClass('spackage'+value.id);
                        $(row).find('td').eq(3).addClass('text-right');
                    });
                }
            });
        }
    });
}

function salesPromoList(value){
    $.ajax({
        type: "GET",
        url: "/item/promo/"+value.promoId,
        dataType: "JSON",
        success:function(data){
            row = sPromo.row.add([
                data.promo.name,
                '<button data-id="'+value.id+'" class="btn btn-primary btn-xs pushSalesPromo" type="button" data-toggle="tooltip" data-placement="top" title="Add/Remove"><i class="fa fa-angle-double-right"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(1).addClass('text-right');
        }
    });
}

$(document).on('click','.pushSalesPromo',function(){
    $(this).removeClass('btn-primary pushSalesPromo');
    $(this).addClass('btn-danger pullSalesPromo');
    $(this).find('i').attr('class','fa fa-angle-double-left');
    salesPromo($(this).attr('data-id'));
});

$(document).on('click','.pullSalesPromo',function(){
    $(this).removeClass('btn-danger pullSalesPromo');
    $(this).addClass('btn-primary pushSalesPromo');
    $(this).find('i').attr('class','fa fa-angle-double-right');
    $('.spromo'+$(this).attr('data-id')).each(function(){
        var row = rowFinder(this);
        sList.row(row).remove().draw();
    })
});

function salesPromo(id){
    $.ajax({
        type: "GET",
        url: "/warranty/sales/promo/"+id,
        dataType: "JSON",
        success:function(value){
            $.ajax({
                type: "GET",
                url: "/item/promo/"+value.promoId,
                dataType: "JSON",
                success:function(data){
                    $.each(data.promo.product,function(key1,value1){
                        if(value1.product.isOriginal!=null){
                            part = (value1.product.isOriginal == 'type1' ? ' - '+type1 : type2)
                        }else{
                            part = '';
                        }
                        row = sList.row.add([
                            value1.quantity,
                            value1.product.brand.name+' - '+value1.product.name+part+' ('+value1.product.variance.name+')',
                            data.promo.name,
                            '<input type="hidden" name="promoProduct[]" value="'+value1.product.id+'"><input type="hidden" name="salesPromo[]" value="'+value.id+'"><input type="text" class="form-control qty text-right" id="qty" name="promoProductQty[]" value="0" required>'
                        ]).draw().node();
                        $(row).find('.qty').inputmask({ 
                            alias: "integer",
                            prefix: '',
                            allowMinus: false,
                            min: 0,
                            max: value.quantity*value1.quantity,
                        });
                        $(row).find('td').eq(0).addClass('text-right');
                        $(row).find('td').eq(0).addClass('spromo'+value.id);
                        $(row).find('td').eq(3).addClass('text-right');
                    });
                }
            });
        }
    });
}

$(document).on('click','#salesSubmit',function(e){
    $.ajax({
        type: 'POST',
        url: '/warranty/sales/create',
        data: $('#salesForm').serialize(),
        success:function(data){
            if(data.message==0){
                e.preventDefault();
                if(data.product.isOriginal!=null){
                    part = (data.product.isOriginal == 'type1' ? ' - '+type1 : type2)
                }else{
                    part = '';
                }
                productString = data.product.brand.name+' - '+data.product.name+part+' ('+data.product.variance.name+')';
                $('#salesError').append(
                    '<div id="alert" class="alert alert-danger alert-dismissible fade in">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    '<h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>' +
                    'Check your inventory status on '+productString+
                    '</div>'
                )
            }else{
                window.location.reload();
            }
        }
    });
});
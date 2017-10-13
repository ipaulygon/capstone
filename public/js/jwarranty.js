var jList = $('#jobList').DataTable({
    'responsive': true,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});

var jProduct = $('#jobProduct').DataTable({
    'responsive': true,
    "ordering" : false,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});

var jService = $('#jobService').DataTable({
    'responsive': true,
    "ordering" : false,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});

var jPackage = $('#jobPackage').DataTable({
    'responsive': true,
    "ordering" : false,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});
var jPromo = $('#jobPromo').DataTable({
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

$(document).on('click','#jobsObtain',function(){
    id = $(this).attr('data-id');
    $('#jobId').val(id);
	$.ajax({
        type: "GET",
        url: "/warranty/job/"+id,
        dataType: "JSON",
        success:function(data){
            console.log(data);
            $('#jobList').DataTable().clear().draw();
            $('#jobProduct').DataTable().clear().draw();
            $('#jobService').DataTable().clear().draw();
            $('#jobPackage').DataTable().clear().draw();
            $('#jobPromo').DataTable().clear().draw();
            //product
            $.each(data.job.product,function(key,value){
                jobProductList(value);
            });
            //service
            $.each(data.job.service,function(key,value){
                jobServiceList(value);
            });
            //package
            $.each(data.job.package,function(key,value){
                jobPackageList(value);
            });
            //promo
            $.each(data.job.promo,function(key,value){
                jobPromoList(value);
            });
        }
    });
	$('#jobModal').modal('show');
});

function jobProductList(value) {
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
            row = jProduct.row.add([
                productString,
                '<button data-id="'+value.id+'" class="btn btn-primary btn-xs pushJobProduct" type="button" data-toggle="tooltip" data-placement="top" title="Add/Remove"><i class="fa fa-angle-double-right"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(1).addClass('text-right');
        }
    });
}

$(document).on('click','.pushJobProduct',function(){
    $(this).removeClass('btn-primary pushJobProduct');
    $(this).addClass('btn-danger pullJobProduct');
    $(this).find('i').attr('class','fa fa-angle-double-left');
    jobProduct($(this).attr('data-id'));
});

$(document).on('click','.pullJobProduct',function(){
    $(this).removeClass('btn-danger pullJobProduct');
    $(this).addClass('btn-primary pushJobProduct');
    $(this).find('i').attr('class','fa fa-angle-double-right');
    $('.jproduct'+$(this).attr('data-id')).each(function(){
        var row = rowFinder(this);
        jList.row(row).remove().draw();
    })
});

function jobProduct(id) {
    $.ajax({
        type: "GET",
        url: '/warranty/job/product/'+id,
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
                    row = jList.row.add([
                        value.quantity,
                        productString,
                        '',
                        '<input type="hidden" name="product[]" value="'+data.product.id+'"><input type="hidden" name="jobProduct[]" value="'+value.id+'"><input type="text" class="form-control qty text-right" id="qty" name="productQty[]" value="0" required>'
                    ]).draw().node();
                    $(row).find('.qty').inputmask({
                        alias: "integer",
                        prefix: '',
                        allowMinus: false,
                        min: 0,
                        max: value.quantity,
                    });
                    $(row).find('td').eq(0).addClass('text-right');
                    $(row).find('td').eq(0).addClass('jproduct'+value.id);
                    $(row).find('td').eq(3).addClass('text-right');
                }
            });
        }
    });
}

function jobServiceList(value) {
    $.ajax({
        type: "GET",
        url: "/item/service/"+value.serviceId,
        dataType: "JSON",
        success: function(data) {
            row = jService.row.add([
                data.service.name +' - '+data.service.size+' ('+data.service.category.name+')',
                '<button data-id="'+value.id+'" class="btn btn-primary btn-xs pushJobService" type="button" data-toggle="tooltip" data-placement="top" title="Add/Remove"><i class="fa fa-angle-double-right"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(1).addClass('text-right');
        }
    });
}

$(document).on('click','.pushJobService',function(){
    $(this).removeClass('btn-primary pushJobService');
    $(this).addClass('btn-danger pullJobService');
    $(this).find('i').attr('class','fa fa-angle-double-left');
    jobService($(this).attr('data-id'));
});

$(document).on('click','.pullJobService',function(){
    $(this).removeClass('btn-danger pullJobService');
    $(this).addClass('btn-primary pushJobService');
    $(this).find('i').attr('class','fa fa-angle-double-right');
    $('.jservice'+$(this).attr('data-id')).each(function(){
        var row = rowFinder(this);
        jList.row(row).remove().draw();
    })
});

function jobService(id) {
    $.ajax({
        type: "GET",
        url: '/warranty/job/service/'+id,
        dataType: 'JSON',
        success: function(value){
            $.ajax({
                type: "GET",
                url: "/item/service/warranty/"+value.serviceId,
                dataType: "JSON",
                success: function(data) {
                    row = jList.row.add([
                        '',
                        data.service.name +' - '+data.service.size+' ('+data.service.category.name+')',
                        '',
                        '<input type="hidden" name="service[]" value="'+data.service.id+'"><input type="hidden" name="jobService[]" value="'+value.id+'">'
                    ]).draw().node();
                    $(row).find('td').eq(0).addClass('text-right');
                    $(row).find('td').eq(0).addClass('jservice'+value.id);
                    $(row).find('td').eq(3).addClass('text-right');
                }
            });
        }
    });
}

function jobPackageList(value){
    $.ajax({
        type: "GET",
        url: "/item/package/"+value.packageId,
        dataType: "JSON",
        success:function(data){
            row = jPackage.row.add([
                data.package.name,
                '<button data-id="'+value.id+'" class="btn btn-primary btn-xs pushJobPackage" type="button" data-toggle="tooltip" data-placement="top" title="Add/Remove"><i class="fa fa-angle-double-right"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(1).addClass('text-right');
        }
    });
}

$(document).on('click','.pushJobPackage',function(){
    $(this).removeClass('btn-primary pushJobPackage');
    $(this).addClass('btn-danger pullJobPackage');
    $(this).find('i').attr('class','fa fa-angle-double-left');
    jobPackage($(this).attr('data-id'));
});

$(document).on('click','.pullJobPackage',function(){
    $(this).removeClass('btn-danger pullJobPackage');
    $(this).addClass('btn-primary pushJobPackage');
    $(this).find('i').attr('class','fa fa-angle-double-right');
    $('.jpackage'+$(this).attr('data-id')).each(function(){
        var row = rowFinder(this);
        jList.row(row).remove().draw();
    })
});

function jobPackage(id){
    $.ajax({
        type: "GET",
        url: "/warranty/job/package/"+id,
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
                        row = jList.row.add([
                            value1.quantity,
                            value1.product.brand.name+' - '+value1.product.name+part+' ('+value1.product.variance.name+')',
                            data.package.name,
                            '<input type="hidden" name="packageProduct[]" value="'+value1.product.id+'"><input type="hidden" name="jobProductPackage[]" value="'+value.id+'"><input type="text" class="form-control qty text-right" id="qty" name="packageProductQty[]" value="0" required>'
                        ]).draw().node();
                        $(row).find('.qty').inputmask({ 
                            alias: "integer",
                            prefix: '',
                            allowMinus: false,
                            min: 0,
                            max: value.quantity*value1.quantity,
                        });
                        $(row).find('td').eq(0).addClass('text-right');
                        $(row).find('td').eq(0).addClass('jpackage'+value.id);
                        $(row).find('td').eq(3).addClass('text-right');
                    });
                    $.each(data.package.service,function(key1,value1){
                        row = jList.row.add([
                            '',
                            value1.service.name+' - '+value1.service.size+' ('+value1.service.category.name+')',
                            data.package.name,
                            '<input type="hidden" name="packageService[]" value="'+value1.service.id+'"><input type="hidden" name="jobServicePackage[]" value="'+value.id+'">'
                        ]).draw().node();
                        $(row).find('td').eq(0).addClass('text-right');
                        $(row).find('td').eq(0).addClass('jpackage'+value.id);
                        $(row).find('td').eq(3).addClass('text-right');
                    });
                }
            });
        }
    });
}

function jobPromoList(value){
    $.ajax({
        type: "GET",
        url: "/item/promo/"+value.promoId,
        dataType: "JSON",
        success:function(data){
            row = jPromo.row.add([
                data.promo.name,
                '<button data-id="'+value.id+'" class="btn btn-primary btn-xs pushJobPromo" type="button" data-toggle="tooltip" data-placement="top" title="Add/Remove"><i class="fa fa-angle-double-right"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(1).addClass('text-right');
        }
    });
}

$(document).on('click','.pushJobPromo',function(){
    $(this).removeClass('btn-primary pushJobPromo');
    $(this).addClass('btn-danger pullJobPromo');
    $(this).find('i').attr('class','fa fa-angle-double-left');
    jobPromo($(this).attr('data-id'));
});

$(document).on('click','.pullJobPromo',function(){
    $(this).removeClass('btn-danger pullJobPromo');
    $(this).addClass('btn-primary pushJobPromo');
    $(this).find('i').attr('class','fa fa-angle-double-right');
    $('.jpromo'+$(this).attr('data-id')).each(function(){
        var row = rowFinder(this);
        jList.row(row).remove().draw();
    })
});

function jobPromo(id){
    $.ajax({
        type: "GET",
        url: "/warranty/job/promo/"+id,
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
                        row = jList.row.add([
                            value1.quantity,
                            value1.product.brand.name+' - '+value1.product.name+part+' ('+value1.product.variance.name+')',
                            data.promo.name,
                            '<input type="hidden" name="promoProduct[]" value="'+value1.product.id+'"><input type="hidden" name="jobProductPromo[]" value="'+value.id+'"><input type="text" class="form-control qty text-right" id="qty" name="promoProductQty[]" value="0" required>'
                        ]).draw().node();
                        $(row).find('.qty').inputmask({ 
                            alias: "integer",
                            prefix: '',
                            allowMinus: false,
                            min: 0,
                            max: value.quantity*value1.quantity,
                        });
                        $(row).find('td').eq(0).addClass('text-right');
                        $(row).find('td').eq(0).addClass('jpromo'+value.id);
                        $(row).find('td').eq(3).addClass('text-right');
                    });
                    $.each(data.promo.service,function(key1,value1){
                        row = jList.row.add([
                            '',
                            value1.service.name+' - '+value1.service.size+' ('+value1.service.category.name+')',
                            data.promo.name,
                            '<input type="hidden" name="promoService[]" value="'+value1.service.id+'"><input type="hidden" name="jobServicePromo[]" value="'+value.id+'">'
                        ]).draw().node();
                        $(row).find('td').eq(0).addClass('text-right');
                        $(row).find('td').eq(0).addClass('jpromo'+value.id);
                        $(row).find('td').eq(3).addClass('text-right');
                    });
                }
            });
        }
    });
}

$(document).on('click','#jobSubmit',function(e){
    $.ajax({
        type: 'POST',
        url: '/warranty/job/create',
        data: $('#jobForm').serialize(),
        success:function(data){
            window.location.reload();
        }
    });
});
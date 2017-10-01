var deactivate = null;
var reactivate = null;
var update = null;
var updateLink = null;
function deactivateShow(id){
    deactivate = id;
    $('#deactivateModal').modal('show');
}
$('#deactivate').on('click', function (){
    $('#del'+deactivate).submit();
});
function reactivateShow(id){
    reactivate = id;
    $('#reactivateModal').modal('show');
}
$('#reactivate').on('click', function (){
    $('#reactivate'+reactivate).submit();
});
$(document).on('change','#showDeactivated',function(){
    if($(this).prop('checked')){
        $('#inactiveTable').addClass('active');
        $('#activeTable').removeClass('active');
    }else{
        $('#inactiveTable').removeClass('active');
        $('#activeTable').addClass('active');
    }
});
function deactivateAdmin(id){
    deactivate = id;
    $('#keyDeactivate').val('');
    $('#deactivateAdmin').modal('show');
}

function updateAdmin(id,type){
    update = id;
    updateLink = type;
    if(userType==1){
        $.ajax({
            type: 'POST',
            url: '/item/admin',
            data: {key: 'admin'},
            success: function(data){
                window.location.replace('/'+updateLink+'/'+update+'/edit');
            }
        });
    }else{
        $('#keyUpdate').val('');
        $('#updateAdmin').modal('show');
    }
}

$('#adminUpdate').on('click',function(){
    key = $('#keyUpdate').val();
    $.ajax({
        type: 'POST',
        url: '/item/user',
        data: {key: key},
        success: function(data){
            if(!data.message){
                $('#notif').append(
                    '<div id="alert" class="alert alert-danger alert-dismissible fade in">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    '<h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>' +
                    'Invalid Key' +
                    '</div>'
                );
                $('#updateAdmin').modal('hide');
            }else{
                window.location.replace('/'+updateLink+'/'+update+'/edit');
            }
            setTimeout(function (){
                $('#alert').alert('close');
            },2000);
        }
    });
});

$('#adminDeactivate').on('click',function(){
    key = $('#keyDeactivate').val();
    $.ajax({
        type: 'POST',
        url: '/item/user',
        data: {key: key},
        success: function(data){
            if(!data.message){
                $('#notif').append(
                    '<div id="alert" class="alert alert-danger alert-dismissible fade in">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    '<h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>' +
                    'Invalid Key' +
                    '</div>'
                );
            }else{
                $('#del'+deactivate).submit();
            }
            setTimeout(function (){
                $('#alert').alert('close');
            },2000);
        }
    });
});
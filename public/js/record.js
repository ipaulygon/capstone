var deactivate = null;
var reactivate = null;
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
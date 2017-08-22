var tList = $('#techList').DataTable({
    'responsive': true,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});

$('#infoTechnician').click(function(){
    $('#techModal').modal('show');
});
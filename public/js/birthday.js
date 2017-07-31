$('#bday').datepicker().on('changeDate',function(){
    getAge();
});

function getAge(){
    today = new Date();
    birthDate = new Date($('#bday').val());
    age = today.getFullYear() - birthDate.getFullYear();
    m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) 
    {
        age--;
    }
    $('#labelAge').text('Age: '+age);
}
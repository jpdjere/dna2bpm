$(document).ready(function() {

$(".datepicker").datepicker({
                viewMode: 'years',
                dateFormat: 'dd-mm-yy'
            });



});



function displayDate() {
    document.getElementById("demo").innerHTML = Date();
}
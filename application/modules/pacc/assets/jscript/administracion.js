
$(document).ready(function () {

     $('#boton-consultar').click(function(){
         
                $.ajax({
                type: "POST",
                url: globals.base_url + 'pacc/administracion/consultar/'+$("#date-picker1").val()+'/'+$("#date-picker2").val(),
                success: function(data) {
                    $('#tabla-administracion').replaceWith(data);
                }
            });
         
    $("#hide").show();
    
     }); 



});//



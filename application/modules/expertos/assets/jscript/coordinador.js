/**
 * Main JS
 * Author: Gabriel Fojo
**/
$(document).ready(function(){

        $("#buscador-proyectos").click(function() {
             
             $.ajax({
                 type: "POST",
                 url: globals.base_url + 'expertos/expertos/reload_tabla/'+ $("#texto").val() ,
                 success: function(data) {
                     $('#tabla-buscador').replaceWith(data);
               }
             });
            
         }); 
         
});
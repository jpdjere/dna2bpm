/**
 * Main JS
 * Author: Gabriel Fojo
**/
$(document).ready(function(){

         $("#buscador-estado").click(function() {
             
             $.ajax({
                 type: "POST",
                 url: globals.base_url + 'fondosemilla/semilla/reload_reportes_incubadora/'+ $("#Inc").val() ,
                 success: function(data) {
                     $('#tabla-estado').replaceWith(data);
               }
             });
            
         });
});
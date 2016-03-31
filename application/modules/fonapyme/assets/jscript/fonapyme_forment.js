/**
 * dna2/inbox JS
 * 
 **/
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
	


  $('#descargar_f').click(function(e) {
    //alert();
   //e.preventDefault();
    $.ajax({
                //type: "POST",
               
                url: globals.base_url + 'fonapyme/fonapyme_forment/descarga1/',
                //url:'http://localhost/dna2bpm/fonapyme/fonapyme_forment/descarga_excell/',
                dataType: "json",
                //data:{hola:'hola'},
                
                success: function(result) {
                    
                    //console.log(result.tabla);
                    //$("#col2").html(result.tabla);
                    //cargar_param();
                    //alert('Pasa ajax');
                    
                }
            });
    
    
  
  
  
 
  });
 
});




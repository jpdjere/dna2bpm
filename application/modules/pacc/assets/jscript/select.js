$(document).ready(function () { 
      
         $("#Provincia").change(function() {

             $.ajax({
                 type: "POST",
                 url: globals.base_url + 'pacc/incubar/reload_filter_partidos/' + $("#Provincia").val(),
                 success: function(data) {
                     $('#select-load').html(data);
                 }
             });
            
         });
        
        
        $("#buscador-incubadoras").click(function() {
            $.ajax({
                type: "POST",
                url: globals.base_url + 'pacc/incubar/reload_tabla_provincia_localidad/'+$("#Provincia").val()+'/'+$("#Partido").val(),
                success: function(data) {
                    $('#tabla-body').replaceWith(data);
                }
            });
            
          $('#tabla-body').show();
          
        });
        
        $("#exportar-incubadoras").click(function() {
            $.ajax({
                type: "POST",
                url: globals.base_url + 'pacc/incubar/reload_tabla_provincia_localidad_excell/'+$("#Provincia").val()+'/'+$("#Partido").val(),
                
                success: function() {
                    window.open(globals.base_url + 'pacc/incubar/reload_tabla_provincia_localidad_excell/'+$("#Provincia").val()+'/'+$("#Partido").val());
                   
                }
            });
          
          
        });
        
        
        
        
         $("#buscador-estado").click(function() {
             
             $.ajax({
                 type: "POST",
                 url: globals.base_url + 'pacc/incubar/estado_incubadora/'+ $("#Inc").val() ,
                 success: function(data) {
                     $('#tabla-estado').replaceWith(data);
               }
             });
            
         });
        
      
    $(function() {
	$('#buscador-incubadoras').click(function(e){
	 	e.preventDefault();
	 	var l = Ladda.create(this);
	 	l.start();
	 	$.post("incubar/reload_tabla_provincia_localidad/"+$("#Provincia").val()+'/'+$("#Partido").val(), 
	 	    { data : null },
	 	  function(response){
	 	    console.log(response);
	 	  }, "json")
	 	.always(function() { l.stop(); });
	 	return false;
	});
	
});
        
 });
/**
 * dna2/inbox JS
 * 
 **/
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
	



$(".calendar").datepicker();

var changeYear = $( ".calendar" ).datepicker( "option", "changeYear" );
var changeMonth = $( ".calendar" ).datepicker( "option", "changeMonth" );
var cant_arch = 0;
$( ".calendar" ).datepicker( "option", "changeMonth", true );
$( ".calendar" ).datepicker( "option", "changeYear", true );
$( ".calendar" ).datepicker( "option", "yearRange", "1920:2013" );

$(function() {
    $('#desde').datepicker({
        changeYear: true,
        dateFormat: "dd-mm-yy",
        firstDay: 1,
        dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
        monthNames: 
            ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
            "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        monthNamesShort: 
            ["Ene", "Feb", "Mar", "Abr", "May", "Jun",
            "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
});
    
  });
$(function() {
    $( "#hasta" ).datepicker({ 
        changeYear: true,
        dateFormat: "dd-mm-yy",
        firstDay: 1,
        dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
        monthNames: 
            ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
            "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        monthNamesShort: 
            ["Ene", "Feb", "Mar", "Abr", "May", "Jun",
            "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"] });
  });
  
  $('#busca').click(function() {
  
      var desde = document.getElementById('desde').value; 
      var hasta = document.getElementById('hasta').value; 
      var desde_date = new Date(desde);
      var hasta_date = new Date(hasta);
    if(desde_date >= hasta_date){ 
        alert("La Fecha Desde: "+ desde + " debe ser menor que la Fecha Hasta: " + hasta);
        location.reload();
        }
    else{
        $.ajax({
                //type: "POST",
               
                url: globals.base_url + 'bonita/bonita_reportes/bonita_tabla_region/'+$("#desde").val()+'/'+$("#hasta").val()+'/'+$("#exportar").val(),
                dataType : "json",
              success: function(result) {
                  $("#col3").html(result.tabla);
                  $("#col2").html("<div id='col2_graf' style='width:100%; height:100%'></div>");
                  
                  $("#col2_graf").highcharts(result.grafico);
                  
                   }
            });
       
        
        
        
    }
      
  });
 
  
});




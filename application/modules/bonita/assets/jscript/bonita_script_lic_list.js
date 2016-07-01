/**
 * dna2/inbox JS
 * 
 **/
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
	
function replaceAll( text, busca, reemplaza ){
    while (text.toString().indexOf(busca) != -1)
    text = text.toString().replace(busca,reemplaza);
    return text;
}

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
            "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]});
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

    $('#nuevo').click(function() {
        $.ajax({
            url: globals.base_url + 'bonita/bonita_licitaciones/bonita_licitaciones_nueva/',
            dataType : "json",
            success: function(result) {
                $("#col2").html(result.tabla);
                cargar_param();
            }
        });
    });
  
    function cargar_param(id_mongo){
        
        $("#cargarform").validate({
            
            rules: {
                resolucion:{
                    required: true,
                },
                fechalic:{
                  required: true,  
                },
                cmax:{
                    required: true,
                },
                maxeeff:{
                    required: true,
                },
                obs:{
                    required: true,
                    minlength: 2
                }
            },
                
            messages: {
                resolucion:{
                    required: "Por favor ingrese la resolucion.",
                },
                fechalic:{
                    required: "Por favor ingrese la fecha de la licitación.",
                },
                cmax:{
                    required: "Por favor ingrese el cupo máximo.",
                },
                maxeeff:{
                    required: "Por favor ingrese el máximo por entidad financiera.",
                },
                obs:{
                    required: "Por favor Ingrese una observación",
			        minlength: "Por favor ingrese al menos 2 caracteres."
                }
    		}
		});
        
        $('#cmax').priceFormat({
            prefix: '',
            centsLimit: 0,
            centsSeparator: ',',
            thousandsSeparator: '.'
        });

        $('#maxeeff').priceFormat({
            prefix: '',
            centsLimit: 0,
            centsSeparator: ',',
            thousandsSeparator: '.'
        });
        
        $("#fechalic").datepicker();
        
        $('#cargarform').submit(function(e) {
            e.preventDefault();
            if ($("#cargarform").valid() == false){
                alert('Por favor complete los campos solicitados!');
            }else{
                var fields = $("#cargarform").serializeArray();
                var resolucion=fields[0].value;
                var fechalic=fields[1].value;
                var cmax = fields[2].value;
                var maxeeff = fields[3].value;
                cmax = replaceAll(cmax,".", "");
                maxeeff = replaceAll(maxeeff,".", "");
                fields[2].value=parseInt(cmax);
                fields[3].value=parseInt(maxeeff);
                var fechanueva = fechalic.split("/");
                fechanueva = new Date(fechanueva[0], fechanueva[1], fechanueva[2]); 
                if(isNaN(fechanueva.getTime())){
                    alert("La fecha ingresada es inválida!");
                }else if(cmax*1<maxeeff*1){
                    alert("El cupo máximo debe ser mayor que el máximo por entidad financiera!");
                }else if(cmax*1==0 || maxeeff*1==0){
                    alert("El cupo máximo y el máximo por entidad financiera no pueden ser cero.");
                }else{
                    if(id_mongo==null){
                        $.ajax({
                            type: "POST",
                            data:{"fields":fields},
                            url: globals.base_url + 'bonita/bonita_licitaciones/bonita_licitaciones_nueva_licitacion/',
                            success: function(result) {
                                location.reload();
                            }
                        });
                    }else{
                        $.ajax({
                            type: "POST",
                            data:{"fields":fields, "id_mongo":id_mongo},
                            url: globals.base_url + 'bonita/bonita_licitaciones/bonita_licitaciones_licitacion_editar_cargar/',
                            success: function(result) {
                                location.reload();
                            }
                        });
                    }
                }
            }
       });
    }
    
    $('#table_lic a').click(function(e) {
        e.preventDefault();
        var id_mongo = $(this).attr("data-id");
        var resolucion = $(this).attr("data-resolucion");
        var fecha = $(this).attr("data-fechalic");
        var cmd = $(this).attr("data-cmd");
        var cmax = $(this).attr("data-cmax");
        var maxeeff = $(this).attr("data-maxeeff");
        var obs = $(this).attr("data-obs");
        if(cmd == 'borrar'){
            $.ajax({
                type: "POST",
                data:{"id_mongo":id_mongo},
                url: globals.base_url + 'bonita/bonita_licitaciones/bonita_licitaciones_licitaciones_borrar/',
                success: function(result) {
                    location.reload();
                }
            });
        }else{
            $.ajax({
                type: "POST",
                data:{"id_mongo":id_mongo, "resolucion":resolucion, "fechalic":fecha,"cmax":cmax, "maxeeff":maxeeff, "obs":obs},
                url: globals.base_url + 'bonita/bonita_licitaciones/bonita_licitaciones_licitaciones_editar/',
                dataType : "json",
                success: function(result) {
                    $("#col2").html(result.tabla);
                    cargar_param(id_mongo);
                }
            });
        }
    });
});
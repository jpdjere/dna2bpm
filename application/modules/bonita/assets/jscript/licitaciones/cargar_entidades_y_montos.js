/**
 * dna2/inbox JS
 * 
 **/
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var cmax;

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

cargar_param();

    function cargar_param(id_mongo){
        
        $("#form_entidades").validate({
            
            rules: {
                    entidad:{
                    required: true,
                },
                monto:{
                    required: true,
                }
            },
                
            messages: {
                entidad:{
                    required: "Por favor ingrese la entidad.",
                },
                monto:{
                    required: "Por favor ingrese el monto.",
                }
    		}
		});
        
        $('#monto').priceFormat({
            prefix: '',
            centsLimit: 0,
            centsSeparator: ',',
            thousandsSeparator: '.'
        });

        $('#form_entidades').submit(function(e) {
            e.preventDefault();
            if ($("#form_entidades").valid() == false){
                alert('Por favor complete los campos solicitados!');
            }else{
                var fields = $("#form_entidades").serializeArray();
                var entidad=fields[0].value;
                var monto=fields[1].value;
                var monto = replaceAll(monto,".", "");
                var id_mongo = $('#tabla_datos').attr("data-id");
                if(monto==0){
                    alert("El monto debe ser mayor a cero.")
                }else if(parseInt(monto*1000000)>parseInt(cmax)){
                    alert("El monto debe ser menor al cupo máximo.")
                }else{
                    $("#guardar").prop("disabled",true);
                    $.ajax({
                        type: "POST",
                        data:{"id_mongo":id_mongo, "entidad":entidad, "monto":monto},
                        url: globals.base_url + 'bonita/licitaciones/cargar_nuevo_monto/',
                        success: function(result) {
                            location.reload();
                        }
                    });
                }
            }
       });
    }
    
    $('#tabla_datos a').click(function(e) {
        console.log("click");
        e.preventDefault();
        var id_licitacion = $(this).attr("data-id_licitacion");
        var id_entidad = $(this).attr("data-id_entidad");
        $.ajax({
            type: "POST",
            data:{"id_licitacion":id_licitacion,"id_entidad":id_entidad},
            url: globals.base_url + 'bonita/licitaciones/borrar_monto/',
            dataType : "json",
            success: function(result) {
                location.reload();
            }
        });
    });
    
    get_cmax();
    
    $("#guardar").prop("disabled",false);
    
    function getURLParameter(name) {
        return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [null, ''])[1].replace(/\+/g, '%20')) || null;
    }
    
    function get_cmax(){
        var id=getURLParameter("id");
        $.ajax({
            type: "POST",
            data:{"id_licitacion":id},
            url: globals.base_url + 'bonita/licitaciones/obtener_cmax/',
            success: function(result) {
                cmax=result;
            }
        });
    }
    
    $("#cerrar_licitacion").click(function(e) {
        var id=getURLParameter("id");
        $.ajax({
            type: "POST",
            data:{"id_licitacion":id},
            url: globals.base_url + 'bonita/licitaciones/cerrar_licitacion/',
            success: function(result) {
                //location.reload();
                window.location.replace(globals.base_url+'bonita/licitaciones/menu_reportes/');
            }
        });
    });
});
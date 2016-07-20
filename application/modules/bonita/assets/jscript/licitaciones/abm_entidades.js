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
            url: globals.base_url + 'bonita/licitaciones/form_nueva_entidad/',
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
    			    rsocial:{
    			        required: true,
    			        minlength: 3
    			    },
    			    ent_cuit:{
    			        required: true,
    			        minlength: 13
    			    },
    			    obs:{
    			        required: true,
    			        minlength: 2
    			    }
    			},
                
                messages: {
                    rsocial:{
                        required: "Por favor Ingrese Razón Social.",
					    minlength: "Por favor ingrese al menos 3 caracteres."
                    },
                    ent_cuit:{
                        required: "Por favor ingrese el CUIT.",
					    minlength: "Por favor ingrese al menos 11 números."
                    },
                    obs:{
                        required: "Por favor Ingrese una observación",
					    minlength: "Por favor ingrese al menos 2 caracteres."
                    }
                    
    			}
			});
        
        $("#ent_cuit").mask("99-99999999-9",{placeholder:""});
        
        $('#cargarform').submit(function(e) {
            e.preventDefault();
            if ($("#cargarform").valid() == false){
                alert('Por favor complete los campos solicitados!');
            }else{
                var fields = $("#cargarform").serializeArray();

                if(id_mongo == null){
                    $.ajax({
                        type: "POST",
                        data:{"fields":fields},
                        url: globals.base_url + 'bonita/licitaciones/cargar_nueva_entidad/',
                        success: function(result) {
                            location.reload();
                        }
                    });
                }else{
                    console.log("editar");
                    $.ajax({
                        type: "POST",
                        data:{"fields":fields, "id_mongo":id_mongo},
                        url: globals.base_url + 'bonita/licitaciones/cargar_editar_entidad/',
                        success: function(result) {
                            location.reload();
                        }
                    });
                }
            }
        });
    }

    $('#table_ent a').click(function(e) {
        e.preventDefault();
        var id_mongo = $(this).attr("data-id");
        var cmd = $(this).attr("data-cmd");
        var rsocial = $(this).attr("data-rsocial");
        var ent_cuit = $(this).attr("data-ent_cuit");
        var obs = $(this).attr("data-obs");
        
        if(cmd == 'borrar'){
            $.ajax({
                type: "POST",
                data:{"id_mongo":id_mongo},
                url: globals.base_url + 'bonita/licitaciones/borrar_entidad/',
                success: function(result) {
                    location.reload();
                }
            });
        }
        
        $.ajax({
            type: "POST",
            data:{"id_mongo":id_mongo, "rsocial":rsocial, "ent_cuit":ent_cuit, "obs":obs},
            url: globals.base_url + 'bonita/licitaciones/form_editar_entidad/',
            dataType : "json",
            success: function(result) {
                
                $("#col2").html(result.tabla);
                cargar_param(id_mongo);
            }
        });
    });
});
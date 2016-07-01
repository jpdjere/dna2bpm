/**
 * dna2/inbox JS
 * 
 **/
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
	




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
  //$("#cargarform").validate();
   
  
  /*$('#cargarform').submit(function(e) {
                         e.preventDefault();
                         console.log('SUBMIT');
                         var fields = $("#cargarform").serializeArray();
                    
                    console.log(fields);
                         
                     });*/
  
  $('#nuevo').click(function() {
  
    
    $.ajax({
                //type: "POST",
               
                url: globals.base_url + 'bonita/bonita_licitaciones/bonita_licitaciones_nueva/',
                dataType : "json",
                success: function(result) {
                    $("#col2").html(result.tabla);
                    cargar_param();
                    
                }
            });
    });
  
    function cargar_param(entidad){
    
        $(".calendar").datepicker();

var changeYear = $( ".calendar" ).datepicker( "option", "changeYear" );
var changeMonth = $( ".calendar" ).datepicker( "option", "changeMonth" );
var cant_arch = 0;
$( ".calendar" ).datepicker( "option", "changeMonth", true );
$( ".calendar" ).datepicker( "option", "changeYear", true );
$( ".calendar" ).datepicker( "option", "yearRange", "1920:2013" );

$(function() {
    $('#fecha').datepicker({
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
    
    
    /*
        
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
                    //console.log(fields);
                    
                    if(entidad == null){
                    
                        $.ajax({
                                type: "POST",
                                
                                data:{"fields":fields},
                                       
                                url: globals.base_url + 'bonita/bonita_licitaciones/bonita_licitaciones_entidad_nueva_cargar/',
                                //dataType : "json",
                                success: function(result) {
                                    //$("#col2").html(result.tabla);
                                    //console.log(result);
                                    location.reload();
                                }
                            });
                    }else{
                        $.ajax({
                                type: "POST",
                                
                                data:{"fields":fields, "entidad":entidad},
                                       
                                url: globals.base_url + 'bonita/bonita_licitaciones/bonita_licitaciones_entidad_editar_cargar/',
                                //dataType : "json",
                                success: function(result) {
                                    //$("#col2").html(result.tabla);
                                    console.log(entidad);
                                    location.reload();
                                }
                            });
                        
                    }        
                    //
                    
                }
                            //var fields = $("#cargarform").serializeArray();
                            
                            //console.log(fields);
                           
            
           });*/
    }   
    
    
    
    $('#table_ent a').click(function(e) {
        e.preventDefault();
        var entidad = $(this).attr("data-id");
        var cmd = $(this).attr("data-cmd");
        var rsocial = $(this).attr("data-rsocial");
        var ent_cuit = $(this).attr("data-ent_cuit");
        var obs = $(this).attr("data-obs");
        //console.log(entidad);
        //console.log(cmd);
        if(cmd == 'borrar'){
            $.ajax({
                                type: "POST",
                                data:{"id_mongo":entidad},
                                       
                                url: globals.base_url + 'bonita/bonita_licitaciones/bonita_licitaciones_entidad_borrar/',
                                //dataType : "json",
                                success: function(result) {
                                    //$("#col2").html(result.tabla);
                                    //console.log("BORRADO!");
                                    //console.log(result);
                                    location.reload();
                                }
                            });
        }
        if(cmd == 'editar'){
            $.ajax({
                                type: "POST",
                                
                                data:{"id_mongo":entidad, "rsocial":rsocial, "ent_cuit":ent_cuit, "obs":obs},
                                
                                url: globals.base_url + 'bonita/bonita_licitaciones/bonita_licitaciones_entidad_editar/',
                                dataType : "json",
                                success: function(result) {
                                    $("#col2").html(result.tabla);
                                     cargar_param(entidad);
                                    //console.log("BORRADO!");
                                    //console.log(result);
                                    //location.reload();
                                }
                            });
            
        
            
        }
        
        
        
        
    });
    
    
    
    
    
    function editar_entidad(){
        
        alert();
    }
    
    
});




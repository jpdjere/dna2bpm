/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor. --- ----
 */

jQuery(document).ready(function($) {

  $.ajax({
  dataType: "json",
  url:  $(location).attr('href') +"api/status",  
  success: function( rtn ) {
    var print_ready = (rtn .ready)? rtn .ready : 0;
    $("#status_ready").html(print_ready);
  	$("#status_waiting").html(rtn .waiting);
  	$("#status_revision").html(rtn .revision);
  	$("#status_F1272").html(rtn .F1272);
  	$("#status_F1273").html(rtn .F1273);
  	$("#status_queue").html(rtn .total_queue);
  }






});
 

$.ajax({
  dataType: "json",
  url:  $(location).attr('href') +"api/status_vinculadas",  
  success: function( rtn ) {   
    $("#count_vinculadas").html(rtn .count);
  }
  




});


$(document).on("click", "#fecha_entrada", function(){ 
  $.ajax({
  url:  $(location).attr('href') +"api/get_fecha_entrada/"+$('#s_cuit').text(),
  success: function(data) {   
    alert(data);
  }
  });
});

$(document).on("click", "#fecha_proceso", function(){ 
  $.ajax({
  url:  $(location).attr('href') +"api/get_fecha_proceso/"+$('#s_cuit').text(),
  success: function(data) {   
    alert(data);
  }
  });
});

$(document).on("click", "#fecha_salida", function(){ 
  $.ajax({
  url:  $(location).attr('href') +"api/get_fecha_salida/" +$('#s_cuit').text(),  
  success: function( data ) {   
    alert(data);
  }
  });
});


});


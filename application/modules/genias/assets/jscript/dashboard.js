/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$( document ).ready(function() {





//$( ".datepicker" ).datepicker({dateFormat: "dd-mm-yy"});
$('.aprobar').click(function(){
   window.location=$(this).attr('url');
});
 $('#dp3').datepicker();

//== VALIDATE == //
$("#form_goals").validate({
rules: {
cantidad: "required",
desde: "required"
},
messages: {
cantidad: "Debe colocar cantidad",
desde: "Debe elegir una fecha"
},
submitHandler: function(form) {
    var data =$('#form_goals').serializeArray();
    if(!navigator.onLine)return;
        
    $.ajax(
   {
      /* this option */
      async: false,
      cache: false,
      type: "POST",
      dataType: "text",
      url: globals.module_url+'add_goal',
      data:{'data':data},
      success:function(resp){
      }
   });

    location.reload();
}
}
);



//$('#form_goals a').click(function(){
//    var data=$('#form_goals').serializeArray();
//
//    $.post(globals.module_url+'add_goal',{'data':data},function(resp){
//      location.reload();
//
//    });
//});

$(".detalle").click(function(){
   $(this).next('.observaciones').slideToggle();
});

// localStorage guardo datos usuario
var userdata={idu:1,genia:1};
localStorage['userdata']="";

// Desabilito los anchos que etan desabled 
$('a[disabled]').one('click', function(e){
e.preventDefault();
});

// ==== TABS ==== //
//$('.nav-tabs li a').click(function(e){
//    var id=$(this).attr('href');
//    $('.tab').hide();
//    $(id).show('fast');
//    
//    e.preventDefault();
//});

 $('#dashboard_tab1 a:first').tab('show');



});


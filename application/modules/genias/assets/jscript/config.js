/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$( document ).ready(function() {


$( ".datepicker" ).datepicker();


// Config

$("#new_project").click(function(){
    var dummy=$('#dummy').html();
    $('.accordion-inner .form-inline:last').before(dummy);
});

$('.btn-remove').live('click',function(){
    $(this).parent().remove();
});

$("#save_project").click(function(){
    var data=$('[name="form_projects"]').serializeArray();

   $.post(globals.module_url+'config_set_projects',{'data':data},function(resp){  
     $(".alert").append(resp).fadeIn('slow');
   });
     
});





});


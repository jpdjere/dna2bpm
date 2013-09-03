/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (window.applicationCache) {
    applicationCache.addEventListener('updateready', function() {
            window.location.reload();
    });
}

$( document ).ready(function() {





//$( ".datepicker" ).datepicker({dateFormat: "dd-mm-yy"});
$('.aprobar').click(function(){
   window.location=$(this).attr('url');
});

 $('#dp3').datepicker(); 
 $('.dp').datepicker();
  
  

//== VALIDATE == //
//
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

/*==== UPDATE META====*/

$('button.guardar').click(function(){
    if(!navigator.onLine)return;
   var meta=$(this).parents('.meta');
   var desde=meta.find('[name="desde"]').val();
   var obs=meta.find('[name="observaciones"]').val();
   var metaid=meta.find('[name="metaid"]').val();
   var proyecto=meta.find('[name="metas_proyecto"]').val(); 
   var data={'desde':desde,'observaciones':obs,'metaid':metaid,'proyecto':proyecto};
    $.ajax(
   {
      /* this option */
      async: true,
      cache: false,
      type: "POST",
      dataType: "text",
      url: globals.module_url+'update_goal',
      data:{'data':data},
      success:function(resp){
          var html='<div class="alert alert-success" style="margin-top:10px"><button type="button" class="close" data-dismiss="alert">&times;</button>Se han guardado sus cambios.</div>';
          meta.find('.well').append().show('slow');
            $(html).hide().appendTo($('.well',meta)).fadeIn('300');
      }
   });
   
});

/*==== DELETE META====*/

$('.bt_delete').click(function(e){
  if(!navigator.onLine)return;
    e.preventDefault();
   
  var meta=$(this).parents('.meta');
  var metaid=meta.find('[name="metaid"]').val();
 
bootbox.confirm("Seguro que desea eliminar la meta?", function(result) {
if(result){
  $.post(globals.module_url+'remove_goal',{metaid:metaid},function(resp){
      if(resp==0){
          meta.detach();
      }
  }); 
}
}); 
 

});


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

$('.nav-tabs a').click(function(e){
    var code=$(this).attr('href').split('-');
    if($(this).attr('href')=="#tab_resumen"){
        $('.meta').show();
        $('.ultree').show();
    }else{
        $('.ultree').hide();
      $('[data-genia]').each(function(index){
       var genia=$(this).attr('data-genia');
       if(genia!=code[1]){
           $(this).hide();
       }else{
           $(this).show();
       } 
        //  alert(genia+"/"+code[1]);
        });
    }


});

$('.ul_collapse').on('click',function(e){
  $(this).next('UL').slideToggle();
});
//function onUpdateReady() {
// // alert('found new version!');
//  location.reload();
//}
//window.applicationCache.addEventListener('updateready', onUpdateReady);
//if(window.applicationCache.status === window.applicationCache.UPDATEREADY) {
//  onUpdateReady();
//}



});


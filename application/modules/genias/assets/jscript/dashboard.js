/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$( document ).ready(function() {



$('.aprobar').click(function(){
   window.location=$(this).attr('url');
});

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
    if(offline)return;
        
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
    if(offline)return;
   var meta=$(this).parents('.meta');
   var desde=meta.find('[name="desde"]').val();
   var obs=meta.find('[name="observaciones"]').val();
   var metaid=meta.find('[name="metaid"]').val();
   var proyecto=meta.find('[name="metas_proyecto"]').val(); 
//   var proyecto_nombre=meta.find('[name="metas_proyecto"] [value="'+proyecto+'"]').text();
   var data={'desde':desde,'observaciones':obs,'metaid':metaid,'proyecto':proyecto};
    $.ajax(
   {
      /* this option */
      async: false,
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
  if(offline)return;
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

//==== METAS Open/Close 
$('.meta_open').on('click',function(e){
e.preventDefault();
var icon=$(this).html();

if(icon=='<i class="fa fa-chevron-circle-down"></i>'){
	$(this).html('<i class="fa fa-chevron-circle-up"></i>');
}else{
	$(this).html('<i class="fa fa-chevron-circle-down"></i>');
}
$(this).parent().next('.meta_body').slideToggle('fast');


});


$('#dashboard_tab1 a:first').tab('show');

$('.nav-tabs li a').click(function(e){

    var code=$(this).attr('href').split('-');
    if($(this).attr('href')=="#tab_resumen"){
    	$('[data-genia]').show();
    }else{
      $('[data-genia]').each(function(index){
	       var genia=$(this).attr('data-genia');
	       if(genia!=code[1]){
	           $(this).hide();
	       }else{
	           $(this).show();
	       } 
       });
    }


});

//==== VISITAS ==== //

// evita el scroll molesto
//$( document ).on( "click", ".mypopover", function(e) {
//e.preventDefault();
//});

// Oculto los popover cuando cierro el accordion
$( document ).on( "click", ".ul_collapse", function() {
  $(this).next('UL').slideToggle();
});

// Cargo visitas politico
 $.post(globals.module_url+'get_resumen_visitas','',function(data){
	 $('#wrapper_visitas').html(data);
 });


 // Cargo visitas institucional
 $.post(globals.module_url+'get_resumen_visitas_instituciones','',function(data){
	 $('#wrapper_visitas_instituciones').html(data);
 });
 
 
// cambio el mes 
$('#dp_metas').datepicker().on('changeDate',function(ev){

    var mes=ev.date.toISOString();
    
    // Pyme
    $.post(globals.module_url+'get_resumen_visitas',{'mes':mes},function(data){
   	 $('#wrapper_visitas').html(data);
    });
    // Institucional
    $.post(globals.module_url+'get_resumen_visitas_instituciones',{'mes':mes},function(data){
   	 $('#wrapper_visitas_instituciones').html(data);
    });

    
   // $('#wrapper_visitas_instituciones').load(globals.module_url+'get_resumen_visitas_instituciones',{'mes':mes}); 

}); 

// Visitas info
$(document).on('click','.bt_info',function(){
	var info=$(this).attr('data-info');
	$('#myModal').find('.modal-header').html('Detalle visita');
	$('#myModal').find('.modal-body').html(info);
	$('#myModal').modal('show');
});

// Empresa info
$(document).on('click','.bt_info_empresa',function(){
	var info=$(this).attr('data-info');
	$('#myModal').find('.modal-header').html('Detalle empresa');
	$('#myModal').find('.modal-body').html(info);
	$('#myModal').modal('show');
});

// Fin ready
});




function onUpdateReady() {
 // alert('found new version!');
  location.reload();
}
window.applicationCache.addEventListener('updateready', onUpdateReady);
if(window.applicationCache.status === window.applicationCache.UPDATEREADY) {
  onUpdateReady();
}

        
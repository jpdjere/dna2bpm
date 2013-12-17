/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * TEST 
 */


var tasks= [];
var tasks666= [];


$( document ).ready(function() {

$('#dp3').datepicker();

// Creo los indices
$.each(globals.proyectos, function( index, myproy ) {
tasks[myproy.id]=new Array();
});


// ==== MANEJO OFFLINE ==== //


// Offline no puede hacer un soto
$('.disabled','a[disabled]').live('click',function(e){
    e.preventDefault();
});

if(!offline){
// Si esta conectado
$.each(globals.proyectos, function( index, value ) {
mongo_get_tasks(value.id);
localStorage['tasks'+value.id]=JSON.stringify(tasks[value.id]);

});
localStorage['tasks666']=JSON.stringify(tasks666);
//localStorage.clear();
}else{
// Si no esta conectado traigo del storage
    localstorage_get_tasks();
}

// ____ OFFLINE ____ //
// 
// Armo array de agendas
var agendas=[{
            events: tasks666,
            color: '#CCCCCC',     
            textColor: 'white' 
       }]

$.each(globals.proyectos, function( index, myproy ) {
agendas.push({
            events: tasks[myproy.id],            
            color: myproy.bgcolor,     
            textColor: myproy.color 
       });
});



// ==== CALENDARIO ==== //

$('#calendar').fullCalendar({

    eventSources: agendas,
    monthNames:['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio',
 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
dayNamesShort:['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
dayNames:['Domingo', 'Lunes', 'Marzo', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
buttonText:{month:'mes',week:'semana',day:'dia',today:'hoy'},
    eventClick: function(calEvent, jsEvent, view) {

        $('#detalle input[name="id"]').val(calEvent.id);
        $('#detalle input[name="title"]').val(calEvent.title);      
        $('#detalle input[name="dia"]').val(calEvent.dia);
        $('#detalle select[name="hora"]').val(calEvent.hora);
        $('#detalle select[name="minutos"]').val(calEvent.minutos);
        $('#detalle select[name="proyecto"]').val(calEvent.proyecto);
        $('#detalle textarea[name="detail"]').val(calEvent.detail);

        if(calEvent.finalizada==1){
          $('#detalle input[name="finalizada"]').attr("checked","checked");  
        }else{
          $('#detalle input[name="finalizada"]').removeAttr("checked");  
        }    
        if(navigator.onLine){
            $('#bt_delete').removeClass('disabled');
            $('#bt_form').removeClass('disabled');
        }
        
        
    },
    header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
    }

});




// ==== SAVE / UPDATE ==== //

$("form").validate({
rules: {
title: "required",
dia: "required",
proyecto: "required"
},
messages: {
title: "Debe colocar un tiulo",
dia: "Debe elegir una fecha",
proyecto: "Debe elegir un proyecto"
},
submitHandler: function(form) {
    var form =$('#detalle form').serializeArray();
    if(!navigator.onLine)return;
        
    $.ajax(
   {
      /* this option */
      async: false,
      cache: false,
      type: "POST",
      dataType: "text",
      url: globals.module_url+'add_task',
      data:{'data':form},
      success:function(resp){
      }
   });

    location.reload();
}
}
);

// ==== CLEAR ==== //

$('#bt_clear').click(function(){

        $('#detalle input[name="id"]').val('');
        $('form')[0].reset();
        $('#bt_form').addClass('disabled');
        $('#bt_delete').addClass('disabled');
 
});

/*
 *  DELETE
 */

$('#bt_delete').click(function(){
    if($(this).hasClass('disabled'))return;
        if(!navigator.onLine)return;
    var id=$('#detalle input[name="id"]').val();
    $('form')[0].reset();
        $.ajax(
   {
      /* this option */
      async: false,
      cache: false,
      type: "POST",
      dataType: "text",
      url: globals.module_url+'remove_task',
      data:{'id':id},
      success:function(resp){
          //localStorage.removeItem(resp);     
      }
   });
   location.reload();
   

});

/*
 *  LOAD FORM
 */
 
$('#bt_form').click(function(){
    var id=$('#detalle input[name="id"]').val();
    var proy=$('#detalle select[name="proyecto"]').val();

    if($(this).hasClass('disabled'))return;
    $.each(globals.proyectos, function( index, value ) {
    if(value.id==proy)
        location.href=globals.module_url+value.link+'?task='+id;
    });
});



		
});



// Traigo las tareas del localstorages
//function localstorage_get_tasks(){
//for (i = 0; i < window.localStorage.length; i++) {
//    key = window.localStorage.key(i);
//
//    if (/tasks.+/.test(key)) {
//        var myjson=JSON.parse(localStorage[key]);
//        var target=(myjson['finalizada']==1)?(666):(myjson['proyecto']);
//        tasks[target].push(myjson);
//    }
//}
//;
//}

function localstorage_get_tasks(){
// Trae las tareas del locastorage
$.each(globals.proyectos, function( index, value ) {
tasks[value.id]=JSON.parse(localStorage['tasks'+value.id]);
});
// Tareas ya realizadas 
tasks666=JSON.parse(localStorage['tasks666']);
}


// Traigo las tareas de mongo
function mongo_get_tasks(s){

url=globals.module_url+"print_tasks/"+s;

$.ajax(
   {
      /* this option */
      async: false,
      cache: false,
      type: "POST",
      dataType: "text",
      url: url,
      success:function(resp){
          if(resp){
            var myjson=JSON.parse(resp);             
            $.each( myjson, function( k, v ) {
                if(v.finalizada==1){
                    tasks666.push(v);
                }else{
                    tasks[s].push(v);
                }
            });
          }

      }
   });


}



/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


var tasks= new Array();
tasks[0]=new Array();
tasks[666]=new Array();



$( document ).ready(function() {
    
    
$('.disabled','a[disabled]').live('click',function(e){
    e.preventDefault();
});

// Desabilito los anchos que etan desabled 
$('a[disabled]').one('click', function(e){
e.preventDefault();
});

// ==== OFFLINE ==== //

if(navigator.onLine){
mongo_get_tasks(0);

//localStorage.clear();

localStorage['tasks0']=JSON.stringify(tasks[0]);
localStorage['tasks666']=JSON.stringify(tasks[666]);

}else{
localstorage_get_tasks();
buttons_offline();
}




// Despliego el calendario
$('#calendar').fullCalendar({

    eventSources:[
       {
            events: tasks[0],            
            color: '#C6372C',     // an option!
            textColor: 'white' // an option!
       },
       {
            events: tasks[666],
            color: '#CCCCCC',     // an option!
            textColor: 'white' // an option!
       } 
       
    ],
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

//$( ".datepicker" ).datepicker({dateFormat: "dd-mm-yy"});
$('#dp3').datepicker();
/*
 *  SAVE / UPDATE
 */

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

/*
 *  CLEAR FORM
 */

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
    if($(this).hasClass('disabled'))return;
    location.href=globals.module_url+'form_empresas_alt?task='+id;
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

tasks[0]=JSON.parse(localStorage['tasks0']);
tasks[666]=JSON.parse(localStorage['tasks666']);
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
              alert(resp);
            var myjson=JSON.parse(resp);             
            $.each( myjson, function( k, v ) {
                if(v.finalizada==1){
                    tasks[666].push(v);
                }else{
                    tasks[s].push(v);
                }
            });
          }

      }
   });


}


function buttons_offline(){
$('#bt_delete').addClass('disabled');
$('#bt_save').addClass('disabled');
$('#bt_clear').addClass('disabled');
}
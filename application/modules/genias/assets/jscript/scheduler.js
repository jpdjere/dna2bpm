/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


tasks= new Array();
tasks[1]=new Array();
tasks[2]=new Array();
tasks[3]=new Array();
tasks[4]=new Array();
tasks[666]=new Array();

$( document ).ready(function() {

localstorage_get_tasks();
//mongo_get_tasks(1);


// Localstorage
//
//
//tasks= new Array();
//tasks[1]=new Array();
//tasks[2]=new Array();
//tasks[3]=new Array();
//tasks[4]=new Array();
//tasks[666]=new Array();
//
//
//for (i = 0; i < window.localStorage.length; i++) {
//    key = window.localStorage.key(i);
//
//    if (/tasks.+/.test(key)) {
//        var myjson=JSON.parse(localStorage[key]);
//        var target=(myjson['finalizada']==1)?(666):(myjson['proyecto']);
//        tasks[target].push(myjson);
//    }
//}


 //Storage
//console.log(tasks.toString());
//localStorage.clear();
//var events = JSON.parse(tasks[0]);




$('#calendar').fullCalendar({

    eventSources:[
       {
            events: tasks[1],
            color: '#C6372C',     // an option!
            textColor: 'white' // an option!
       } ,
        {
            events: tasks[2],
            color: '#5D9736',     // an option!
            textColor: 'white' // an option!
       } ,
       {
            events: tasks[3],
            color: '#365C97',     // an option!
            textColor: 'white' // an option!
       } ,
       {
            events: tasks[4],
            color: '#823697',     // an option!
            textColor: 'white' // an option!
       } 
       ,
       {
            events: tasks[666],
            color: '#CCCCCC',     // an option!
            textColor: 'white' // an option!
       } 
       
    ],
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
        $('#bt_form').removeClass('disabled');
        $('#bt_delete').removeClass('disabled');
    },
    header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
    }

});

$( ".datepicker" ).datepicker({dateFormat: "dd-mm-yy"});

// Validator / SUBMIT

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
    $.post(globals.module_url+'add_task',{'data':form},function(resp){ 

    // Replico en el localStorage
    var myjson=JSON.parse(resp);
    var idu=myjson['idu'];
    var event_id=myjson['id'];
    localStorage['tasks.'+idu+'.'+event_id]=resp;  
    $('#calendar').fullCalendar( 'refetchEvents' );
    
    });

    //location.reload(); 
}
}
);

$('#bt_clear').click(function(){
        $('#detalle input[name="id"]').val('');
        $('form')[0].reset();
        $('#bt_form').addClass('disabled');
        $('#bt_delete').addClass('disabled');
 
});

$('#bt_delete').click(function(){
    if($(this).hasClass('disabled'))return;
    var id=$('#detalle input[name="id"]').val();
    $('form')[0].reset();
    $.post(globals.module_url+'remove_task',{'id':id},function(resp){ 
        localStorage.removeItem(resp);
    });
   $('#calendar').fullCalendar( 'refetchEvents' );
});


$('#bt_form').click(function(){
    var id=$('#detalle input[name="id"]').val();
    if($(this).hasClass('disabled'))return;
    location.href=globals.module_url+'form/'+id;
});



		
});

function localstorage_get_tasks(){

for (i = 0; i < window.localStorage.length; i++) {
    key = window.localStorage.key(i);

    if (/tasks.+/.test(key)) {
        var myjson=JSON.parse(localStorage[key]);
        var target=(myjson['finalizada']==1)?(666):(myjson['proyecto']);
        tasks[target].push(myjson);
    }
}

}

function mongo_get_tasks(p){

$.post(globals.module_url+"get_tasks/1",'',function(data){
    var myjson=JSON.parse(data);
//    for each (var task in myjson) {
//        console.log(task.proyecto);
//    }

//$.each( myjson, function( key, value ) {
//    $.each( value, function( k, v ) {
//    alert( k + ": " + v );
//    });
//});

    //var target=(myjson['finalizada']==1)?(666):(myjson['proyecto']);
    tasks[1].push(myjson);
});

}
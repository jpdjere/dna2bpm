/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$( document ).ready(function() {

// Localstorage
//
//tasks= new Array();
//for (i = 0; i < window.localStorage.length; i++) {
//
//                key = window.localStorage.key(i);
//
//                if (/tasks.+/.test(key)) {
//                    tasks.push(localStorage[key]);
//                    //localStorage.removeItem(key);
//                    
//                }
//}
 //Storage
//console.log(tasks.toString());
//localStorage.clear();
//var events = JSON.parse(tasks[0]);

//var it = Iterator(tasks);
//for (var task in it)
//console.log(tasks); 
//tasks= new Array();
//tasks2=JSON.parse(tasks);


$('#calendar').fullCalendar({

    
    eventSources: [          
        // your event source
        {
            url: globals.module_url+"/get_tasks/1",
            type: 'POST',
            error: function() {
                alert('there was an error while fetching events!');
            },
            color: '#C6372C',   // a non-ajax option
            textColor: 'white' // a non-ajax option
        },{
            url: globals.module_url+"/get_tasks/2",
            type: 'POST',
            error: function() {
                alert('there was an error while fetching events!');
            },
            color: '#5D9736',   // a non-ajax option
            textColor: 'white' // a non-ajax option
        },{
            url: globals.module_url+"/get_tasks/3",
            type: 'POST',
            error: function() {
                alert('there was an error while fetching events!');
            },
            color: '#365C97',   // a non-ajax option
            textColor: 'white' // a non-ajax option
        },{
            url: globals.module_url+"/get_tasks/4",
            type: 'POST',
            error: function() {
                alert('there was an error while fetching events!');
            },
            color: '#823697',   // a non-ajax option
            textColor: 'white' // a non-ajax option
        },
        {
            url: globals.module_url+"/get_tasks/666",
            type: 'POST',
            error: function() {
                alert('there was an error while fetching events!');
            },
            color: '#cccccc',   // a non-ajax option
            textColor: 'white' // a non-ajax option
        }

        // any other sources...

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
        $('#bt_form').addClass('disabled');
        $('#bt_delete').addClass('disabled');
        $('form')[0].reset();
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
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$( document ).ready(function() {


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

        // any other sources...

    ],
    eventClick: function(calEvent, jsEvent, view) {

        $('#detalle input[name="eventid"]').val(calEvent.id);
        $('#detalle input[name="title"]').val(calEvent.title);      
        $('#detalle input[name="dia"]').val(calEvent.dia);
        $('#detalle select[name="hora"]').val(calEvent.hora);
        $('#detalle select[name="minutos"]').val(calEvent.minutos);
        $('#detalle select[name="proyecto"]').val(calEvent.proyecto);
        $('#detalle textarea[name="detail"]').val(calEvent.detail);
        $('#bt_submit').text('Actualizar');
    },
    header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
    }

});

$( ".datepicker" ).datepicker({dateFormat: "dd-mm-yy"});
$('#bt_new_task').click(function(){
        $('#detalle input[name="eventid"]').val('');
        $('#detalle input[name="title"]').val('');       
        $('#detalle input[name="dia"]').val('');
        $('#detalle select[name="hora"]').val('12');
        $('#detalle select[name="minutos"]').val('00');
        $('#detalle select[name="proyecto"]').val('');
        $('#detalle textarea[name="detail"]').val('');
});

$('#bt_update_task').click(function(){
    var form =$('#detalle form').serializeArray();
    $.post(globals.module_url+'add_task',{'data':form},function(resp){      
    });
    location.reload();
});
		
});


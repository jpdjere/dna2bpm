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
        $('#detalle input[name="start"]').val(calEvent.start);
        $('#detalle input[name="dia"]').val(calEvent.dia);
        $('#detalle textarea[name="detail"]').text(calEvent.detail);

    },
    header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
    }

});

$( ".datepicker" ).datepicker({dateFormat: "dd-mm-yy"});
$('#bt_submit').click(function(){
    var form =$('#detalle form').serializeArray();
    $.post(globals.module_url+'add_task',{'data':form},function(resp){
      location.reload();
    });
});
		
});


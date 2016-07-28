/**
 * Main JS
 * Author: Gabriel Fojo
**/
$(document).ready(function(){

        $('[data-id]').click(function(e){
            
           var myid = $(this).attr('data-id');
       
           // Inbox
           if(myid=='inbox'){
               location.href=globals['base_url']+"dashboard/inbox";
           }
           
            if(myid=='tramites'){
               
            $('#myModal').modal('toggle');
           
           }       

        });
});
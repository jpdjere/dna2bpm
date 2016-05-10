/**
 * Main JS
 * Author: Gabriel Fojo
**/
$(document).ready(function(){


        console.log('---- users');
        
        $('[data-id]').click(function(e){
            
           var myid = $(this).attr('data-id');
       
           // Inbox
           if(myid=='inbox'){
               location.href=globals['base_url']+"dashboard/inbox/";
           }
           
           
           if(myid=='tramites'){
               $('.tramites_shortcut_extra').fadeToggle();
            
           }       



        });
});
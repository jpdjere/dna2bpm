// Recover Passw JS

$(document).ready(function(){
    
    console.log('---- recover ');
    

    
$( "form" ).submit(function( event ) {
  event.preventDefault();
  var myform=$(this);
  var mail=$(this).find('[name="mail"]').val();
  var url=globals['module_url']+'recover/send';
  
     $.post(url,{mail:mail},function(resp){
         if(resp.status==true){
             var msg ="<p class='text-info '><i class='fa fa-thumbs-o-up' aria-hidden='true'></i> "+resp.msg+"</p>";
         }else{
             var msg ="<p class='text-danger '><i class='fa fa-thumbs-o-down' aria-hidden='true'></i> "+resp.msg+"</p>";
         }
         
         myform.find('.footer').html(msg);
     },'json');
    
  
  
  

});

});

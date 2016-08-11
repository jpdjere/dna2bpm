/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor. --- ----
 */

jQuery(document).ready(function($) {



  $("#consult").validate({
    rules: {
     // simple rule, converted to {required:true}
      cuit: "required"    
    },
    
      submitHandler: function(form) {
          var data=$(form).serializeArray();       
          
      
           /*HIDE*/
          $('#msg_error').hide();
          $('#success_update').hide();
          $('#error_transaccion').hide();
          $('#loading').show();
          var cuit = null;

          var url=base_url+"perfil/expertos_get_afip_data/";                
          $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: function(resp) {                                       
                
              $('.cuit_all').hide();
              var cuit = null;
              cuit = $("#cuit").val();                            
              $('#loading').hide();  
              console.log(resp.msg);
              switch(resp.msg)
              {
                
                case 'success_update':                      
                      $("#a_cuit").html(resp.cuit);                     
                      $('#success_update').show();                                                
                break;

                case 'error_transaccion':
                    $("#b_cuit").html(resp.cuit);                    
                    $('#error_transaccion').show();                       
                break;

                default:
                    $("#error_cuit").html($('#cuit').val());
                    $('#msg_error').show();
                break;
              }
                
            },
            dataType: 'json'
          });
      }
  });
   

  
    

//==  ready
});

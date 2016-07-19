/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor. --- ----
 */

jQuery(document).ready(function($) {

$("form").validate({
  rules: {
   // simple rule, converted to {required:true}
    cuit: "required"
  },
    submitHandler: function(form) {
        var data=$(form).serializeArray();       
        

        /*HIDE*/
        $('#msg_error').hide();
        $('#waiting').hide();
        $('#revision').hide();
        var cuit = null;

        var url=base_url+"afip/consultas/process/";        
        var url_print=base_url+"afip/consultas/certificado/";    
        var url_detail=base_url+"afip/consultas/source/";   
        $.ajax({
          type: "POST",
          url: url,
          data: data,
          success: function(resp) {                                
              $('.cuit_all').hide();
              var cuit = null;
              cuit = $("#cuit").val();
              var status  = resp.status;
              $("a.source").attr("href", url_detail + resp.cuit);
              switch(resp.status)
              {
                case 'ready':                      
                      $('#s_cuit').html(resp.cuit);
                      $("a.certificado").attr("href", url_print + resp.cuit);                      
                      $('#ready').show();  
                break;

                case 'waiting':                      
                      $("#w_cuit").html(resp.cuit);
                      $('#waiting').show();  
                break;

                case 'revision':
                    $("#r_cuit").html(resp.cuit);
                      $('#revision').show();  
                break;

                default:
                    $("#e_cuit").html($('#cuit').val());
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


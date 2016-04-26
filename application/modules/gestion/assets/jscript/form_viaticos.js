/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor. --- ----
 */

jQuery(document).ready(function($) {
   
//console.log('----- Formularios de inscripcion');


$("form").validate({
  rules: {
   // simple rule, converted to {required:true}
    provincia: "required",
    ciudad:"required",
   
    og_email:{
        required:true,
        email:true
    },
    o1_email:{
        required:true,
        email:true
    },
    o2_email:{
        required:true,
        email:true
    }
    
  },
    submitHandler: function(form) {
        var data=$( form ).serializeArray();

        var url=base_url+"gestion/viaticos/process/";
        console.log(url);
        $.ajax({
          type: "POST",
          url: url,
          data: data,
          success: function(resp) {
            console.log(resp);
            $(form).html('');
            if(resp.status){
                $(form).html('');
                $('#msg_ok').show();
            }else{
                $(form).html('');
                $('#msg_error').show();
            }
          },
          dataType: 'json'
        });
    }


});




   

  
    

//==  ready
});


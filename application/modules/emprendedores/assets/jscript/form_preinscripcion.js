/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor. ---
 */

jQuery(document).ready(function($) {
   
console.log('----- Formularios de inscripcion');


$("form").validate({
  rules: {
   // simple rule, converted to {required:true}
    provincia: "required",
    ciudad:"required",
    og_ambito:"required",
    og_nombre:"required",
    og_referente:"required",
    og_ambito:"required",
    og_telefono:"required",
    o1_nombre:"required",
    o1_referente:"required",
    o1_telefono:"required",
    o2_nombre:"required",
    o1_referente:"required",
    o2_telefono:"required",
    espacio_uso:"required",
    espacio_domicilio:"required",
    espacio_m2:"required",
    po_impacto:"required",
    po_uso_esperado:"required",
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

        var url=base_url+"emprendedores/formularios/process/";
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


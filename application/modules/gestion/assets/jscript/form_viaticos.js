/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor. --- ----
 */

jQuery(document).ready(function($) {
   
//console.log('----- Formularios de inscripcion');

    /*hidden labels*/
        $("#interior").hide();
        $("#exterior").hide();


$( "#destino" ).change(function() {
  if($( "#destino" ).val()=='interior'){
      $("#interior").show();
      $("#exterior").hide();
  } else {
     $("#interior").hide();
     $("#exterior").show();
  }
});


//GUARDAR

$("#add_group").click(function(e) {
    
    var sel=$('#select_group').val();
    if(sel=="all")$('#groups_box').html(""); // remove all to avoid repeats

    $.post('http://localhost/dna2bpm/gestion/viaticos/get_option_button',{sel:sel}, function( data ) {
        $('#groups_box').append(data);
    });
    
    console.log($('#groups_box').html());
    
});






//Validat

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


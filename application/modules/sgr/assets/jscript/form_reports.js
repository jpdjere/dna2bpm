/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor. --- ----
 */

jQuery(document).ready(function($) {

var base_url=globals['base_url'];
/* CALENDAR */

var d = new Date();
var strDate = (d.getMonth()) +"-"+d.getFullYear();
$('[id^="input_period_"]').val(strDate);
$('.dp').datepicker();
/*FORM REPORTS*/
    $("#sgr_report").change( function() {
        
        checked_opt = false;  
        var getSgr = $("#sgr_report option:selected").val();       

        if(getSgr==666)
             checked_opt = true;             
        
        $("INPUT[type='checkbox'][@name='sgr_checkbox[]']").attr("checked", checked_opt);   

        if(getSgr!=777){
          $('#checks_sgrs').hide();
        } else {
          $('#checks_sgrs').show();
        }   
    });

   

    $("#form_reports").validate({
    rules: {
            sgr: "required"
        },
        messages: {
            sgr: "(Seleccione)"
        },
  
    submitHandler: function(form) {
        var data=$(form).serializeArray();    

        /*HIDE*/

        $('#show_link').hide();
        $('#show_no_record').hide();
        $('#loading').show();
        var cuit = null;

        var url=base_url+"sgr/reports/new_report/";   
        $.ajax({
          type: "POST",
          url: url,
          data: data,
          success: function(resp) {     
              $('#loading').hide(); 
              if(resp=='ok')
                $('#show_link').show();
              else     
                $('#show_no_record').show();
            },
          dataType: 'json'
        });
    }
});

  /*SHOW LINK IF HAVE TO*/
  $.ajax({
    dataType: "json",
    url:  base_url+"sgr/reports/is_report/",
    success: function( rtn ) { 
      if(rtn)
        $('#show_link').show();
    }
  });
//==  ready
});

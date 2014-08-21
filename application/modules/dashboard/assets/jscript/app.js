/**
 * Main JS
 * Author: Gabriel Fojo
 **/

$(document).ready(function() {

$('.form-extra').ajaxForm({
    target:'#tiles_after section',
    replaceTarget:false
});
    // ==== Reload Widget
    $(document).on('click', '.reload_widget', function(event) {
        event.preventDefault();
        var box = $(this).parents('.box');
        var url = $(this).attr('href');

        $.ajax({
            url: url,
            context: box
        }).done(function(data) {
            $(this).replaceWith(data);
        });
    });

 // ==== Load Tiles 
    $(document).on('click', '.load_tiles_after', function(event) {
        event.preventDefault();
        var box = $(this).parents('.box');
        var url = $(this).attr('href');

        $.ajax({
            url: url,
            context: box
        })
                .done(function(data) {
            $('#tiles_after section').html(data);
        })
                .error(function(jqXHR,textStatus,errorThrown){
                    $('#tiles_after section').html(textStatus+errorThrown);
                })
        ;
    });
    
// ==== Load Modal 
    $(document).on('click', '.load_modal', function(event) {
        event.preventDefault();

        var url = $(this).attr('href');
        var title= $(this).attr('title')?($(this).attr('title')):('Title');
        $.ajax({
            url: url,
            context: document.body
        })
                .done(function(data) {
            $('#myModal').find('.modal-title').html(title);
            $('#myModal').find('.modal-body').html(data);
            $('#myModal').modal('show');

        })
                .error(function(jqXHR,textStatus,errorThrown){
                    $('#tiles_after section').html(textStatus+errorThrown);
                })
        ;
    });

// ==== Make the dashboard widgets sortable Using jquery UI
    $(".connectedSortable").sortable({
        placeholder: "sort-highlight",
        connectWith: ".connectedSortable",
        handle: ".box-header, .nav-tabs",
        forcePlaceholderSize: true,
        zIndex: 999999
    }).disableSelection();
    $(".box-header, .nav-tabs").css("cursor", "move");
    //jQuery UI sortable for the todo list
    $(".todo-list").sortable({
        placeholder: "sort-highlight",
        handle: ".handle",
        forcePlaceholderSize: true,
        zIndex: 999999
    }).disableSelection();
    ;

  //=========== ICHECK 

  //iCheck for checkbox and radio inputs
  $('input[type="checkbox"]').iCheck({
      checkboxClass: 'icheckbox_minimal',
      radioClass: 'iradio_minimal'
  });

  //When unchecking the checkbox
  $(document).on('ifUnchecked', "#check-all", function(event) {
      //Uncheck all checkboxes
      $("input[type='checkbox']", ".table-mailbox").iCheck("uncheck");
  });

  //When checking the checkbox
  $("#check-all").on('ifChecked', function(event) {
  	 $("input[type='checkbox']", ".table-mailbox").iCheck("check");
  });
  
  
//=========== CONFIG PANEL
  
  $(document).on('click','#config_panel_bt',function(e){
	 // $('#config_panel_bt').toggle();

	  if($(this).hasClass('open')){
		  // Close it
		  $('#config_panel_content,#config_panel_bt').animate({
			  right: "-=170",
			  }, 500, function() {
			  // Animation complete.
			  });

		  $(this).removeClass('open');
	  }else{
		  // open it
		  $('#config_panel_content,#config_panel_bt').animate({
			  right: "+=170",
			  }, 500, function() {
			  // Animation complete.
			  });
		  $(this).addClass('open');
	  }

  });
  
  
  $('#bt_pasteboard').on('ifChecked', function(event){
	  	$('#pasteboard').show(500);
  });
  
  $('#bt_pasteboard').on('ifUnchecked', function(event){
	  $('#pasteboard').hide(500);
});


});
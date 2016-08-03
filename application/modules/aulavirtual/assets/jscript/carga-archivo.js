$(document).on('click', '#close-preview', function(){ 
   
});

//INPUT 0

$(function() {

 // Habilita Pop Overs   
$('[data-toggle="popover"]').popover();

    // Create the close button
    var closebtn = $('<button/>', {
        type:"button",
        text: 'x',
        id: 'close-preview',
        style: 'font-size: initial;',
    });
    closebtn.attr("class","close pull-right");
    // Set the popover default content
    // Clear event
    $('.image-preview-clear').click(function(){
        $('.image-preview-filename').val("");
        $('.image-preview-clear').hide();
        $('.image-preview-input input:file').val("");
        $(".image-preview-input-title").text("Examinar"); 
    }); 
    // Create the preview image
    $(".image-preview-input input:file").change(function (){     
        var img = $('<img/>', {
            id: 'dynamic',
            width:250,
            height:200
        });      
        var file = this.files[0];
        var reader = new FileReader();
        // Set preview image into the popover data-content
        reader.onload = function (e) {
            $(".image-preview-input-title").text("Cambiar");
            $(".image-preview-clear").show();
            $(".image-preview-filename").val(file.name);            
            img.attr('src', e.target.result);
        }        
        reader.readAsDataURL(file);
    });  
      $(".image-preview-input input:file").change(function (){     
        var img = $('<img/>', {
            id: 'dynamic',
            width:250,
            height:200
        });      
        var file = this.files[0];
        var reader = new FileReader();
        // Set preview image into the popover data-content
        reader.onload = function (e) {
            $(".image-preview-input-title").text("Cambiar");
            $(".image-preview-clear").show();
            $(".image-preview-filename").val(file.name);            
            img.attr('src', e.target.result);
        }        
        reader.readAsDataURL(file);
    });
        $(".image-preview-input input:file").change(function (){     
        var img = $('<img/>', {
            id: 'dynamic',
            width:250,
            height:200
        });      
        var file = this.files[0];
        var reader = new FileReader();
        // Set preview image into the popover data-content
        reader.onload = function (e) {
            $(".image-preview-input-title").text("Cambiar");
            $(".image-preview-clear").show();
            $(".image-preview-filename").val(file.name);            
            img.attr('src', e.target.result);
        }        
        reader.readAsDataURL(file);
    });

  });
//INPUT1
$(function() {
    // Create the close button
    var closebtn = $('<button/>', {
        type:"button",
        text: 'x',
        id: 'close-preview',
        style: 'font-size: initial;',
    });
    closebtn.attr("class","close pull-right");
    // Set the popover default content
 
    // Clear event
    $('.image-preview-clear1').click(function(){
        $('.image-preview-filename1').val("");
        $('.image-preview-clear1').hide();
        $('.image-preview-input1 input:file').val("");
        $(".image-preview-input-title1").text("Examinar"); 
    }); 
    // Create the preview image
    $(".image-preview-input1 input:file").change(function (){     
        var img = $('<img/>', {
            id: 'dynamic',
            width:250,
            height:200
        });      
        var file = this.files[0];
        var reader = new FileReader();
        // Set preview image into the popover data-content
        reader.onload = function (e) {
            $(".image-preview-input-title1").text("Cambiar");
            $(".image-preview-clear1").show();
            $(".image-preview-filename1").val(file.name);            
            img.attr('src', e.target.result);
        }        
        reader.readAsDataURL(file);
    });  
      $(".image-preview-input1 input:file").change(function (){     
        var img = $('<img/>', {
            id: 'dynamic',
            width:250,
            height:200
        });      
        var file = this.files[0];
        var reader = new FileReader();
        reader.onload = function (e) {
            $(".image-preview-input-title1").text("Cambiar");
            $(".image-preview-clear1").show();
            $(".image-preview-filename1").val(file.name);            
            img.attr('src', e.target.result);
        }        
        reader.readAsDataURL(file);
    });
        $(".image-preview-input1 input:file").change(function (){     
        var img = $('<img/>', {
            id: 'dynamic',
            width:250,
            height:200
        });      
        var file = this.files[0];
        var reader = new FileReader();
        reader.onload = function (e) {
            $(".image-preview-input-title1").text("Cambiar");
            $(".image-preview-clear1").show();
            $(".image-preview-filename1").val(file.name);            
            img.attr('src', e.target.result);
        }        
        reader.readAsDataURL(file);
    });
 });


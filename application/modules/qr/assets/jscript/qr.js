$(document).ready(function(){
    $('#reader').html5_qrcode(
        function(data){
            $('#read').html(data).addClass('alert alert-success');
            escaneo = $('#readHidden').val(data);
            color=$('body').css('background-color');
            $('body').animate({backgroundColor: '#FFF'},200).animate({backgroundColor: color},100);
            
            url=globals.redir;
            
            $.post(url,{'redir': escaneo},function(res){});
            
            
            //$.ajax({type:'post'})
         
            
            
        },
        function(error){
            $('#read_error').html(error);
        }, function(videoError){
            $('#vid_error').html(videoError);
        }
        );
});

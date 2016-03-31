$(document).ready(function(){

    $('#reader').html5_qrcode(
        function(data){
            url=globals.base_url + 'inventory/info'
            $.post(url,{'data': data},function(res){
                $('#result').html(res);
                $('#bt_cam_checkin').removeClass("disabled");         	
            });
            $('#reader_status').html(data);
             $('#mycam').modal('hide');
        },
        function(error){            
            $('#reader_status').html(error);
            console.log(error);
        }, function(videoError){
            $('#reader_status').html(videoError);
             console.log(videoError);
        }
        );  


});

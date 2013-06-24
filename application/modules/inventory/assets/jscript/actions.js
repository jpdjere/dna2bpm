$(document).on('click','#btn_seach',function(){
    type = $("#type option:selected").val();
    code =$("#code").val();
    $('#result').load();
    $.ajax({
        'url': globals.module_url+'info',
        'type': 'POST',
        'data':{
            'type':type,
            'code':code
        },
        'success': function(result){
            $('#result').html(result);     
        }
    });
});

$(document).on('click','#btn_gencode',function(){
    type = $("#type option:selected").val();
    code =$("#code").val();
    $('#result').load();
    $.ajax({
        'url': globals.module_url+'gencode',
        'type': 'POST',
        'data':{
            'type':type,
            'code':code
        },
        'success': function(result){
            $('#result').html(result);     
        }
    });
});

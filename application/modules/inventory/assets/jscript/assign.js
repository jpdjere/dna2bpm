$(document).on('change','#group_assign',function(){
    //console.log($(this),$(this).val());
    idgroup=$('#group_assign option:selected').val();
    $.post(globals.module_url+"get_users/"+idgroup, 
        function(data) {
            var sel = $("#user_assign");
            sel.empty();
            for (var i=0; i<data.length; i++) {
                sel.append('<option value="' + data[i].idu + '">' + data[i].name +' '+data[i].lastname+'</option>');
            }
        }, "json");
});


$(document).on('click','#btn_assign',function(){
    idu = $("#user_assign option:selected").val();
    data =$("#data-assign").val();
    //---claim
    $.post(globals.module_url+"claim",
    {
        'data':data,
        'idu':idu
    },function(res){
        $('#result').html(res);
    });
});

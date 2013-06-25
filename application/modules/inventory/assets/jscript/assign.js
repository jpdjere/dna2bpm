$(document).on('change','#group_select',function(){
    console.log($(this),$(this).val());
    idgroup=$('#group_select option:selected').val();
    $.post(globals.module_url+"get_users/"+idgroup, 
        function(data) {
            var sel = $("#user_select");
            sel.empty();
            for (var i=0; i<data.length; i++) {
                sel.append('<option value="' + data[i].idu + '">' + data[i].name +' '+data[i].lastname+'</option>');
            }
        }, "json");
});


$(document).on('click','#btn_assign',function(){
    idu = $("#user_select option:selected").val();
    data =$("#data").val();
    //---claim
    $.post(globals.module_url+"claim",
    {
        'data':data,
        'idu':idu
    },function(res){
        $('#result').html(res);
    });
});

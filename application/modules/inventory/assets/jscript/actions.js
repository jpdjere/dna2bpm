
$(document).ready(function(){

// ===== CAM info
$(document).on('click', '#bt_cam_info', function(e) {
e.preventDefault();
 $('#mycam').modal();     
});

//==== Cam ON
$('#mycam').on('show.bs.modal', function (event) {
    //=== reader

    $('#reader').html5_qrcode(
        function(data){
            url=globals.base_url + 'inventory/info';
            $.post(url,{'data': data},function(res){
                $('#result').html(res);
                $('#bt_cam_checkin').removeClass("disabled"); 
                    var type = $("[name='mytype']").val();
                    var code = $("[name='mycode']").val();
                    $('body').data('type',type);
                    $('body').data('code',code);
                    $('#expid .alert').removeClass('hidden');
                    $('#expid .alert').html('<strong>'+type+'</strong> '+code);
                    update_bt_state();
        
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


})

//==== Cam OFF
$('#mycam').on('hide.bs.modal', function (event) {
$('#mycam').hide();
})


//==== Manual Search 

$(document).on('click', '#btn_seach', function() {

    $('#result').load();
    type = $("#type option:selected").val();
    code = $("#code").val();
    $.ajax({
        'url': globals.base_url + 'inventory/info',
        'type': 'POST',
        'data': {
            'type': type,
            'code': code
        },
        'success': function(result) {
            $('#result').html(result);
            
            //ExpData
            $('body').data('code',code);
            $('body').data('type',type);
            $('#expid .alert').html('<strong>'+type+'</strong> '+code);
            $('#expid .alert').removeClass('hidden');
            update_bt_state();
        }
    });
});

//==== Manual Generar Code

$(document).on('click', '#btn_gencode', function() {
    type = $("#type option:selected").val();
    code = $("#code").val();

	if(!code.match(/\d{4}\/\d{4}/)){
	  $("#code").parent().addClass('has-error');
	}else{
		$("#code").parent().removeClass('has-error');
	}
    
    $('#result').load();
    $.ajax({
        'url': globals.base_url + 'inventory/gencode',
        'type': 'POST',
        'data': {
            'type': type,
            'code': code
        },
        'success': function(result) {
            $('#myQR .modal-title').html(type+':'+code);
            $('#myQR .modal-body').html(result);
            $('#printboard').html(result);
            $('#myQR').modal('show');
        }
    });
});

//==== Mostrar Expedientes 

$(document).on('click', '#btn_showobjects', function() {
	var boton=$(this);
	boton.html('<i class="fa fa-spinner fa-spin"></i> Buscando');
    idu = $("#user_select option:selected").val();
    idgroup = $("#group_select option:selected").val();
    $.ajax({
        'url': globals.base_url + 'inventory/show_objects',
        'type': 'POST',
        'data': {
            'idu': idu,
            'idgroup': idgroup
        },
        'success': function(result) {
            $('#result').html(result);
            boton.html('Mostrar');
            update_bt_state();
        }
    });
});

//==== Select Group change 

$(document).on('change', '#group_select', function() {
    //console.log($(this),$(this).val());
    idgroup = $('#group_select option:selected').val();
    $.post(globals.base_url + "inventory/get_users/" + idgroup,
            function(data) {
                var sel = $("#user_select");
                sel.empty();
                for (var i = 0; i < data.length; i++) {
                    sel.append('<option value="' + data[i].idu + '">' + data[i].name + ' ' + data[i].lastname + '</option>');
                }
            }, "json");
});

//==== Claim (Manual & CAM)

$(document).on('click', '#btn_claim, #bt_cam_checkin', function(e) {

e.preventDefault();
// cam or manual selection

type = $("#type option:selected").val();
code = $("#code").val();
if(!code){
// if code input has a value use that first, if not i check data variable
var type=$('body').data('type');
var code=$('body').data('code');
console.log('input');
}

    
 if(typeof code =='undefined'){
	 console.log('-- no definido');
 }   

	bootbox.dialog({
		  message: "Desea Reclamar la Carpeta: " + type + ' ' + code + ' ?',
		  title: "Check In",
		  buttons: {
		    success: {
		      label: "Si!",
		      className: "btn-success",
		      callback: function() {
	              type = $("#type option:selected").val();
	              code = $("#code").val();
	              $('#result').load();
	              $.ajax({
	                  'url': globals.base_url + 'inventory/claim',
	                  'type': 'POST',
	                  'data': {
	                      'type': type,
	                      'code': code
	                  },
	                  'success': function(result) {
	                      $('#result').html(result);
	                      update_bt_state();
	                  }
	              });
		      }
		    },
		    danger: {
		      label: "No!",
		      className: "btn-danger",
		      callback: function() {
		      }
		    }
		  }
		});
	
	


})

//==== Boton Asignar a (Manual & CAM)
$(document).on('click', '#btn_assign_to, #bt_cam_assign', function() {
    type = $('body').data('type');
    code =  $('body').data('code');            
                   
    $('#result').load();
    $.ajax({
        'url': globals.base_url + 'inventory/assign_to',
        'type': 'POST',
        'data': {
            'data': globals.base_url + 'inventory/info/' + type + '/' + code,
        },
        'success': function(result) {
            $('#result').html(result);
            update_bt_state();
        }
    });
})

//==== Group Change

$(document).on('change','#group_assign',function(){
    //console.log($(this),$(this).val());
    idgroup=$('#group_assign option:selected').val();
    $.post(globals.base_url+"inventory/get_users/"+idgroup, 
        function(data) {
            var sel = $("#user_assign");
            sel.empty();
            for (var i=0; i<data.length; i++) {
                sel.append('<option value="' + data[i].idu + '">' + data[i].name +' '+data[i].lastname+'</option>');
            }
        }, "json");
});

//==== assign
$(document).on('click','#btn_assign',function(){

    idu = $("#user_assign option:selected").val();
    data =$("#data-assign").val();
    //---claim
    
    //---claim
    $.post(globals.base_url+"inventory/claim",{'data':data,'idu':idu},function(res){
        $('#result').html(res);
        update_bt_state();
    });
    $.post(globals.base_url+"inventory/notify",{'data':data,'idu':idu},function(res){});
});


// VALIDACION CODE
$(document).on('change','#code',function(){
	var code =$(this).val();
	if(!code.match(/\d{4}\/\d{4}/)){
	  $("#code").parent().addClass('has-error');
           $("#btn_gencode").addClass('disabled');
	}else{
	  $("#code").parent().removeClass('has-error');	
           $("#btn_gencode").removeClass('disabled');
	}
});


}); // ./ready



function update_bt_state(){
	console.log('-- Buttons Update State');
	var code = $("body").data("code");
        console.log('-- Buttons Update State'+code);
	if(typeof code =='undefined'){
		$(".showifcode").addClass('disabled');	
	}else{
		 $(".showifcode").removeClass('disabled');
	}


}


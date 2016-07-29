/**
 * dna2/inbox JS
 * 
 **/
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/* global globals*/
function validaciones_especiales(){
    //insertar
	var checkboxgroupcount = $("[form='insertar']:checkbox:checked").length;
	console.log(checkboxgroupcount);
	if(checkboxgroupcount<1){
		$(".insertar-checkbox")[0].setCustomValidity("Completa este campo");
	}else{
		$(".insertar-checkbox")[0].setCustomValidity("");
	}

    //editar
    var checkboxgroupcount2 = $("[form='editar']:checkbox:checked").length;
	console.log(checkboxgroupcount2);
	if(checkboxgroupcount2<1){
		$(".checkbox-edit")[0].setCustomValidity("Completa este campo");
	}else{
		$(".checkbox-edit")[0].setCustomValidity("");
	}
}

$(document).ready(function() {
    
    validaciones_especiales();
    
    $('input[type="checkbox"]').on('ifChecked', function(event){
        validaciones_especiales();
    });
    
    $(".edit").click(function(e){
        
        $("#resolucion").val(e.currentTarget.getAttribute('resolucion'));
        $("#monto").val(e.currentTarget.getAttribute('monto'));
        $("#_id").val(e.currentTarget.getAttribute('mongoid'));
        
        //checkbox destino
        $(".checkbox-edit").each(function(index, element){
            $(element).prop('checked', false);
            $(element).parent().removeClass('checked');
        });
        
        var destinos = e.currentTarget.getAttribute('destino[]').split(",");
        destinos.forEach(function(item, index){
            if(item!=''){
                $("#destino-checkbox-"+item).prop('checked', true);
                $("#destino-checkbox-"+item).parent().addClass('checked');
            }
        });
        validaciones_especiales();
    });
    
    $(".remove").click(function(e){
        $.ajax({
            type: "POST",
            data:{'_id':e.currentTarget.getAttribute('mongoid')},
            url: globals.base_url + 'bonita/prestamos/borrar_monto',
            dataType : "json",
            success: function(result) {
                location.reload();
            }
        });
    });
    
    $("#insertar").submit(function(e){
        $("[value=Insertar]").prop('disabled', true);
        var data = $("#insertar").serializeArray();
        $.ajax({
            type: "POST",
            data:data,
            url: globals.base_url + 'bonita/prestamos/insertar_monto',
            dataType : "json"
        });
    });
    
    $("#editar").submit(function(e){
        var data = $("#editar").serializeArray();
        $.ajax({
            type: "POST",
            data:data,
            url: globals.base_url + 'bonita/prestamos/actualizar_monto',
            dataType : "json"
        });
    });
});
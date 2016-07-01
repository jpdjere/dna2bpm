;
function oculta(id) {
	$("#"+id).hide();
	$("#"+id+"2").prop('disabled', true);
	$("#"+id+"2").val('---');
	$("#"+id).prop('disabled', true);
}

function muestra(id) {
	$("#"+id).show();
	$("#"+id+"2").prop('disabled', false);
	$("#"+id).prop('disabled', false);
}

function validaciones_especiales(){
	//cuit
	var cuit = parseInt($("#cuit").val().replace(/-/g , ""));
	if(isNaN(cuit) || cuit==""){
		$("#cuit")[0].setCustomValidity("Completa este campo");
	}else{
		$("#cuit")[0].setCustomValidity("");
	}
	
	//telefono
	var telefono = parseInt($("#telefono").val().replace(/-/g , "").replace(/\(|\)/g, ""));
	if($("#telefono").val()==""){
		$("#telefono")[0].setCustomValidity("Completa este campo");
	}else if(isNaN(telefono)){
		$("#telefono")[0].setCustomValidity("El teléfono es inválido");
	}else{
		$("#telefono")[0].setCustomValidity("");
	}
	
	//destino_prestamo
	var checkboxgroupcount = $(".destino_prestamo :checkbox:checked").length;
	if(checkboxgroupcount<1){
		$("[name='destino_prestamo[]']")[0].setCustomValidity("Completa este campo");
	}else{
		$("[name='destino_prestamo[]']")[0].setCustomValidity("");
	}

	//todos_los_bancos
	var checkboxgroupcount2 = $(".bancos_otros :checkbox:checked").length;
	if(checkboxgroupcount2<1){
		$("#bancos_otros-checkbox-text")[0].setCustomValidity("Completa este campo");
	}else{
		$("#bancos_otros-checkbox-text")[0].setCustomValidity("");
	}
}


$(document).ready(function(){
	//Los arrays estan definidos en "arrays_de_campos" y se cargan desde el html
	/*global sectores, todos_los_campos, campos_pyme_bancario,
	campos_pyme_no_bancario, campos_gran_empresa*/
	//if (history.forward(1)){alert("¡Cobarde, has vuelto atrás!")}
	
	$("#sector_actividad2").prop('disabled', false);
	$("#tipo_sociedad2").prop('disabled', false);
	$("#provincia2").prop('disabled', false);
	$("#compartir_efis2").prop('disabled', false);
	$("[name='bancos_otros[]']").prop('disabled', false);
	$("#sector_actividad2").val('---');
	
	$("#cuit").mask("99-99999999-9",{placeholder:"_"});
	$("#bancos_otros-checkbox-text")[0].addEventListener("input", function(){
		$("#bancos_otros-checkbox-text").val("");
	}, false);
	
	$("#bancos_otros-checkbox-text").click(function () {
		if($(".contenedor-checkboxes").css('display')=='none'){
			$(".contenedor-checkboxes").css('display','block');
			$(".contenedor-checkboxes").css('float','left');
		}else{
			$(".contenedor-checkboxes").hide();
		}
	});
	
	validaciones_especiales();
	
	$("#telefono")[0].addEventListener("input", function(){validaciones_especiales();}, false);
	$("#cuit").change(function() {
	    validaciones_especiales();
	});
	//$("#cuit")[0].addEventListener("change", function(){validaciones_especiales();}, false);
	$("[name='bancos_otros[]']").change(function() {validaciones_especiales()});
	
	var destinos = document.getElementsByName("destino_prestamo[]");
	for (var i=0; i < destinos.length; i++){
		destinos[i].addEventListener("change", function(){validaciones_especiales();}, false);
	}

	function ocultar_campos(array_campos){
		for (var i=0; i < array_campos.length; i++){
			oculta(array_campos[i]);
		}
	}

	function mostrar_campos(array_campos){
		for (var i=0; i < array_campos.length; i++){
			muestra(array_campos[i]);
		}
	}
	
	function ocultar_todos_los_campos(){
		for (var i=0; i < todos_los_campos.length; i++){
			ocultar_campos(todos_los_campos[i]);
		}
	}

	$("#sector_actividad2").change(function(){
		ocultar_todos_los_campos();
		ocultar_campos(sectores);
		muestra(sectores[$('#sector_actividad2').val()]);
	});
	
	function muestra_campos_pyme(){

		ocultar_todos_los_campos();		
		muestra("tiene_prestamos");
	}

	function muestra_campos_gran_empresa(){

		ocultar_todos_los_campos();
		mostrar_campos(campos_gran_empresa);
		
	}
	
	$.each(sectores, function(i,val){
		$("#"+sectores[i]+"2").change(function(){
			if($("#"+sectores[i]+"2").val()<=3){
				muestra_campos_pyme();
			}else{
				muestra_campos_gran_empresa();
			}
		});
	});
	
	function muestra_campos_pyme_no_bancario(){
		mostrar_campos(campos_pyme_no_bancario);
		oculta("monto_solicitado_bienes_cap");
		oculta("monto_solicitado_inversion_prod");
		oculta("monto_solicitado_otros");
		if ($("#tiene_tramite2").val()!=1) { 			//False
			oculta("concurso_homologado");
		}
	}

	$("#tiene_prestamos2").change(function(){
		
		if($("#tiene_prestamos2").val()==0){ 			//False: no bancario
			oculta("tiene_tramite");
			oculta("clasificacion_deudores");
			ocultar_campos(campos_pyme_bancario);
			muestra_campos_pyme_no_bancario();
		}else{											//True
			oculta("tiene_tramite");
			muestra("clasificacion_deudores");
			ocultar_campos(campos_pyme_no_bancario);
		}
	});
	
	$("#clasificacion_deudores2").change(function(){
		
		if($("#clasificacion_deudores2").val()==0){ 	//False
			muestra("tiene_tramite");
			ocultar_campos(campos_pyme_no_bancario);
		}else{											//True: no bancario
			ocultar_campos(campos_pyme_no_bancario);
			ocultar_campos(campos_pyme_bancario);
			oculta("tiene_tramite");
			muestra_campos_pyme_no_bancario();
		}
	});	
	
	$("#tiene_tramite2").change(function(){
		
		if($("#tiene_tramite2").val()==0){ 				//False: bancario
			ocultar_campos(campos_pyme_no_bancario);
			mostrar_campos(campos_pyme_bancario);
			$("#monto_prestamo2").attr("disabled", false);
			$("#monto_solicitado2").attr("disabled", true);
		}else{											//True: no bancario
			ocultar_campos(campos_pyme_bancario);
			muestra_campos_pyme_no_bancario();
			$("#monto_prestamo2").attr("disabled", true);
			$("#monto_solicitado2").attr("disabled", false);
		}
	});
	
	$("#destino_prestamo_nobanc2").change(function() {
		oculta("monto_solicitado_bienes_cap");
		oculta("monto_solicitado_inversion_prod");
		oculta("monto_solicitado_otros");
		if($("#destino_prestamo_nobanc2").val()==0){
			muestra("monto_solicitado_bienes_cap");
		}else if($("#destino_prestamo_nobanc2").val()==1){
			muestra("monto_solicitado_inversion_prod");
		}else{
			muestra("monto_solicitado_otros");
		}
	});
});
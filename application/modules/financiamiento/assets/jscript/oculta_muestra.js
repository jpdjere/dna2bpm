function oculta(id) {
	$("#"+id).hide();
	$("#"+id+"2").prop('disabled', true);
	$("#"+id+"2").val('---');
}

function muestra(id) {
	$("#"+id).show();
	$("#"+id+"2").prop('disabled', false);		
}

$(document).ready(function(){
	//Los arrays estan definidos en "arrays_de_campos" y se cargan desde el html
	/*global sectores, todos_los_campos, campos_pyme_bancario,
	campos_pyme_no_bancario, campos_gran_empresa*/
	
	$("#sector_actividad2").prop('disabled', false);
	$("#tipo_sociedad2").prop('disabled', false);
	$("#provincia2").prop('disabled', false);
	
	function ocultar_campos(array_campos){
		array_campos.forEach(function(campo){
			oculta(campo);
		});
	}

	function mostrar_campos(array_campos){
		array_campos.forEach(function(campo){
			muestra(campo);
		});
	}
	
	function ocultar_todos_los_campos(){
		todos_los_campos.forEach(function(array_de_campos){
			ocultar_campos(array_de_campos);
		});
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
	
	sectores.forEach(function(sector){
		$("#"+sector+"2").change(function(){
			if($("#"+sector+"2").val()<=3){
				muestra_campos_pyme();
			}else{
				muestra_campos_gran_empresa();
			}
		});
	});
	
	function muestra_campos_pyme_no_bancario(){
		mostrar_campos(campos_pyme_no_bancario);
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
			ocultar_campos(campos_pyme_bancario);
			oculta("tiene_tramite");
			muestra_campos_pyme_no_bancario();
		}
	});	
	
	$("#tiene_tramite2").change(function(){
		
		if($("#tiene_tramite2").val()==0){ 				//False: bancario
			mostrar_campos(campos_pyme_bancario);
			ocultar_campos(campos_pyme_no_bancario);
			$("#monto_prestamo2").attr("disabled", false);
			$("#monto_solicitado2").attr("disabled", true);
		}else{											//True: no bancario
			ocultar_campos(campos_pyme_bancario);
			muestra_campos_pyme_no_bancario();
			$("#monto_prestamo2").attr("disabled", true);
			$("#monto_solicitado2").attr("disabled", false);
		}
	});
});
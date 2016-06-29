/**
 * dna2/inbox JS
 * 
 **/
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 
function muestra(id) {
	$("#"+id).show();
	$("#"+id+"2").prop('disabled', false);		
}

$(document).ready(function() {
	/*global base_url*/

	$.ajax({
		type: "POST",
		url: base_url + 'financiamiento/financiamiento/devuelve_programas_pyme_bancario/',
		data: {"idcase":$("#idcase").val() , "idwf":$("#idwf").val()},
		dataType : "json",
		success: function(result) {
			result.forEach(function(programa){
				muestra(programa);
			});
			muestra("compartir_efis");
		}
	});
});
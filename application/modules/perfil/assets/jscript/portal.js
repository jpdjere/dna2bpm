/**
 * Main JS
 * Author: Gabriel Fojo
**/
$(document).ready(function(){


$(".knob").knob({
    'min':1,
    'max':100,
    'readOnly':true,
    'width':120
});


// Select CUIT
$('#search_empresa').change(function(){
	cuit=$(this).val();
	cuit_clean = cuit.replace(/-/gi, '');
	window.location.href=globals['base_url']+"perfil/empresa/"+cuit_clean;
});


cuit=$("[name='cuit']").val();
cuit_1=cuit.substr(0, 2);
cuit_2=cuit.substr(2, 8);
cuit_3=cuit.substr(10, 1);
cuit=cuit_1+'-'+cuit_2+'-'+cuit_3;
$('#search_empresa').val(cuit);




});
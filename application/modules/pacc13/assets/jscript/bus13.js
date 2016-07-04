/**
 * dna2/inbox JS
 * 
**/
$(document).ready(function(){

//====== crefisGral_BO
	
	$(document).on('click','.pacc3SDAREND_UO',function(e){
		e.preventDefault();
		var url=$(this).attr('href');
		var box=$(this).parents('.box-body');

		$.post( url, function( data ) {
			  $(box).html( data);
			});
	});
	
	
});//

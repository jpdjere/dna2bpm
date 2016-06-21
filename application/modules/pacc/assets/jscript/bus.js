/**
 * dna2/inbox JS
 * 
**/
$(document).ready(function(){

//====== crefisGral_BO
	
	$(document).on('click','.INCUBAR_OM',function(e){
		e.preventDefault();
		var url=$(this).attr('href');
		var box=$(this).parents('.box-body');

		$.post( url, function( data ) {
			  $(box).html( data);
			});
	});
	
	
});//

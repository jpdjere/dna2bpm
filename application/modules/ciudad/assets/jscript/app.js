/**
 * dna2/inbox JS
 * 
**/
$(document).ready(function(){

//====== ciudadGral_BO
	
	$(document).on('click','.ciudadGral_BO',function(e){
		e.preventDefault();
		var url=$(this).attr('href');
		var box=$(this).parents('.box-body');

		$.post( url, function( data ) {
			  $(box).html( data);
			});
	});
	
	
});//



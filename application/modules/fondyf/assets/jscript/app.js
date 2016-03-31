/**
 * dna2/inbox JS
 * 
**/
$(document).ready(function(){

//====== fondyfpp_BO
	
	$(document).on('click','.fondyfpp_BO',function(e){
		e.preventDefault();
		var url=$(this).attr('href');
		var box=$(this).parents('.box-body');

		$.post( url, function( data ) {
			  $(box).html( data);
			});
	});
	
	
});//



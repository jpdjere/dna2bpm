
$(function() {
//-------------------------------
	
// ==== Submit
	
$('form').on('submit',function(e){
	e.preventDefault();
	
	if (Modernizr.localstorage) {
	// ====== Localstorage	
		var myform=$( this ).serializeArray();

		// Guardo el registo
		localStorage['seminario.'+myform[0].value]=JSON.stringify(myform);
			
	// ------ Localstorage			
	}else{
		alert("Su navegador no soporte datos offline");
	}
	
});	


//==== Combos Provincias & Partidos

get_option_provincias();
$('[name="4651"]').on('change',function(e){
	var selection=$(this).val();
	get_option_partidos(selection);
});

//

//============ LOCALSTORAGE ============

for (var i = 0; i < localStorage.length; i++){
   var myKey=localStorage.key(i);
   var res;
   if(rs=myKey.match(/^seminario[.]*/)){
	   // Found item
	   console.log(rs['input']);
   }
}

//==== Carga X CUIT

$('form [name="1695"]').on('change',function(e){
	var cuit=$(this).val();

	if(localStorage['seminario.'+cuit]){
		var data=JSON.parse(localStorage['seminario.'+cuit]);
		var lista={
				
		}
		 jQuery.each( data, function( i, field ) {
			 //
			 lista
//			 if(field.name=='1699'){
//				 $('form [name="'+field.value+'"]').val('test');
//				 console.log(field.value);
//			 }else{
//				 $('form [name="'+field.name+'"]').val(field.value);
//			 }
			 
		 });

	}
});




//--------------------------------
});




//Load Provincias
function get_option_provincias(){
	var options="";
	$.get(globals.module_url + 'assets/json/provincias.json','',function(data){		
		 jQuery.each( data.rows, function( i, field ) {
			 options+="<option value='"+field.value+"'>"+field.text+"</option>\n"; 
		 });
		 $('form [name="4651"]').html(options);
	});	
}

//Load Partidos
function get_option_partidos(prov){
	var options="";
	$.get(globals.module_url + 'assets/json/partidos.json','',function(data){
		
		 jQuery.each( data.rows, function( i, field ) {
			 if(field.idrel==prov){
				 options+="<option value='"+field.value+"'>"+field.text+"</option>\n"; 
			 }
		 });
		 $('[name="1699"]').html(options);
	});	
}





$(document).ready(function(){
	
	$("#param").change(function(){
		
		switch ($(this).val()) {
			case 'cuit':
				$("#value").prop('type', 'text');
				$("#value").mask("99-99999999-9",{placeholder:"_"});
				break;
			
			case 'idcase':
				$("#value").prop('type', 'text');
				$("#value").mask("aaaa",{placeholder:"_"});
				break;
				
			case 'mail':
				$("#value").unmask();
				$("#value").prop('type', 'email');
				break;
				
			default:
				$("#value").prop('type', 'text');
				$("#value").unmask();
				break;
		}
	});
});
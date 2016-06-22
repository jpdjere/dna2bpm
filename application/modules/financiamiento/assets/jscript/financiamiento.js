/**
 * dna2/inbox JS
 * 
 **/
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {

    $("#commentForm").validate({
		rules: {
		    prestamo:{
		        required: true
		    }
		},
		messages: {
		    prestamo:{
		        required: "Por favor seleccione una opción."
		    }
		}
	});
    
    $('#commentForm').submit(function(e) {
        var fields = $("#commentForm").serializeArray();
        if ($("#commentForm").valid() == false){
            e.preventDefault();
            alert('Por favor complete los campos solicitados!');
        }
    });
});
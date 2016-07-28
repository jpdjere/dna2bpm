/**
 * dna2/inbox JS
 * 
 **/
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/* global globals*/

function specials(){
    var config = {placeholder_text_single:"Selecciona una opción", no_results_text:"No se encuentra"};
    
    //other
    $("[name='cuit']").mask("99-99999999-9",{placeholder:"_"});
    $(".calendar").datepicker();
    
    //chosen
    $(".chosen").chosen(config);
}

function destroy_chosen(){
    $(".chosen").chosen("destroy");
}

$(document).ready(function() {
    
    $("#agregar").click(function() {
        
        destroy_chosen();
        
        $('#tabla_principal tbody>tr:last').clone(true).insertAfter('#form_principal tbody>tr:last');
        $('#tabla_principal tbody>tr:last>td>input').val('');
        
        specials();
        
        return false;
    });
    
    $("#quitar").click(function() {
        $('#tabla_principal tr:last').remove();
        return false;
    });
    
    specials();
});
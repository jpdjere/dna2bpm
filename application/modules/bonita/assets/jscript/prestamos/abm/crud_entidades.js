/**
 * dna2/inbox JS
 * 
 **/
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/* global globals*/

$(document).ready(function() {
    
    $(".edit").click(function(e){
        $("#razon_social").val(e.currentTarget.getAttribute('razon_social'));
        var cuit = e.currentTarget.getAttribute('cuit');
        $("#cuit").val(cuit.substring(0,2)+'-'+cuit.substring(2,10)+'-'+cuit.substring(10,12));
        $("#id").val(e.currentTarget.getAttribute('id'));
        $("#razon_social").focus();
    });
    
    $(".remove").click(function(e){
        $.ajax({
            type: "POST",
            data:{'id':e.currentTarget.id},
            url: globals.base_url + 'bonita/prestamos/borrar_entidad',
            dataType : "json",
            success: function(result) {
                location.reload();
            }
        });
    });

    $("#insertar").submit(function(e){
        $("[value=Insertar]").prop('disabled', true);
        var data = $("#insertar").serializeArray();
        data[1]['value'] = data[1]['value'].replace(/-/g, "");
        $.ajax({
            type: "POST",
            data:data,
            url: globals.base_url + 'bonita/prestamos/insertar_entidad',
            dataType : "json"
        });
    });
    
    $("#editar").submit(function(e){
        var data = $("#editar").serializeArray();
        data[1]['value'] = data[1]['value'].replace(/-/g, "");
        $.ajax({
            type: "POST",
            data:data,
            url: globals.base_url + 'bonita/prestamos/actualizar_entidad',
            dataType : "json"
        });
    });
    $("[name='cuit']").mask("99-99999999-9",{placeholder:"_"});
});
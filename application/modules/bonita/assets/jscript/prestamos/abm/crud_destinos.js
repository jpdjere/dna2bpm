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
        $("#destino").val(e.currentTarget.getAttribute('destino'));
        $("#id").val(e.currentTarget.getAttribute('id'));
        $("#destino").focus();
    });
    
    $(".remove").click(function(e){
        $.ajax({
            type: "POST",
            data:{'id':e.currentTarget.id},
            url: globals.base_url + 'bonita/prestamos/borrar_destino',
            dataType : "json",
            success: function(result) {
                location.reload();
            }
        });
    });

    $("#insertar").submit(function(e){
        $("[value=Insertar]").prop('disabled', true);
        var data = $("#insertar").serializeArray();
        $.ajax({
            type: "POST",
            data:data,
            url: globals.base_url + 'bonita/prestamos/insertar_destino',
            dataType : "json"
        });
    });
    
    $("#editar").submit(function(e){
        var data = $("#editar").serializeArray();
        $.ajax({
            type: "POST",
            data:data,
            url: globals.base_url + 'bonita/prestamos/actualizar_destino',
            dataType : "json"
        });
    });
});
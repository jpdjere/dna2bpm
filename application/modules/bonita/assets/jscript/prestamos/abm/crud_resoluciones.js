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
        $("#resolucion").val(e.currentTarget.getAttribute('resolucion'));
        $("#tamano1").val(e.currentTarget.getAttribute('tamano[1]'));
        $("#tamano2").val(e.currentTarget.getAttribute('tamano[2]'));
        $("#tamano3").val(e.currentTarget.getAttribute('tamano[3]'));
        $("#tamano4").val(e.currentTarget.getAttribute('tamano[4]'));
        $("#id").val(e.currentTarget.getAttribute('id'));
    });
    
    $(".remove").click(function(e){
        $.ajax({
            type: "POST",
            data:{'id':e.currentTarget.id},
            url: globals.base_url + 'bonita/prestamos/borrar_resolucion',
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
            url: globals.base_url + 'bonita/prestamos/insertar_resolucion',
            dataType : "json"
        });
    });
    
    $("#editar").submit(function(e){
        var data = $("#editar").serializeArray();
        $.ajax({
            type: "POST",
            data:data,
            url: globals.base_url + 'bonita/prestamos/actualizar_resolucion',
            dataType : "json"
        });
    });
});
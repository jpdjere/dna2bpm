
$(document).ready(function () {

 $(function () {
    /* BOOTSNIPP FULLSCREEN FIX */  
    $('a[href="#modal-nuevo-editar"]').on('click', function(event) {
        event.preventDefault();
        $('#modal-nuevo-editar').modal('show');
    });
});

$(function () {
    /* BOOTSNIPP FULLSCREEN FIX */  
    $('a[href="#modal-solo-editar"]').on('click', function(event) {
        event.preventDefault();
        $('#modal-solo-editar').modal('show');
    });
});
 $(function () {
    /* BOOTSNIPP FULLSCREEN FIX */  
    $('a[href="#modal-contrato"]').on('click', function(event) {
        event.preventDefault();
        $('#modal-contrato').modal('show');
    });
});
 $(function () {
    /* BOOTSNIPP FULLSCREEN FIX */  
    $('a[href="#modal-plan"]').on('click', function(event) {
        event.preventDefault();
        $('#modal-plan').modal('show');
    });
});
 $(function () {
    /* BOOTSNIPP FULLSCREEN FIX */  
    $('a[href="#modal-cronograma"]').on('click', function(event) {
        event.preventDefault();
        $('#modal-cronograma').modal('show');
    });
});
 $(function () {
    /* BOOTSNIPP FULLSCREEN FIX */  
    $('a[href="#modal-pago"]').on('click', function(event) {
        event.preventDefault();
        $('#modal-pago').modal('show');
    });
});


$(document).on('click', '#agregar_plan_de_pagos', function(e) {
    if ($('input[name=radio]:checked','.panel-body').val() == undefined){ alert ("Por favor seleccione un contrato para agregarle un plan de pagos");}
    else{
        $.ajax({
            type: "POST",
            url: 'plan_de_adquisiciones/agregar_pago/'+$('input[name=radio]:checked','.panel-body').val()+'/'+$("#PORCENTAJE").val()+'/'+$("#DIAS").val()+'/'+$("#FECHA_DE_PAGO").val()+'/'+$("#MONTO").val(),
            success: function(data) {
                      location.reload();
                }
        });
    }
    });
    
$(document).on('click', '.fa-trash-o', function(e) {
    
    if ($('input[name=radio]:checked','.panel-body').val() == undefined){ alert ("Por favor seleccione un contrato para eliminar");}
    else{
        $.ajax({
            type: "POST",
            url: globals.base_url + 'pacc/plan_de_adquisiciones/eliminar_contrato/'+$('input[name=radio]:checked','.panel-body').val(),
            success: function(data) {
                      location.reload();
                }
            
        });
    }
    });
    
$(document).on('click', '#edit_fecha_plan_de_pagos', function(e) {
    
    if ($('input[name=radio]:checked','.panel-body').val() == undefined){ alert ("Por favor seleccione un contrato para editar la fecha");}
    else{
        $.ajax({
            type: "POST",
            url: globals.base_url + 'pacc/plan_de_adquisiciones/editar_fechas_pago/'+$('input[name=radio]:checked','.panel-body').val()+'/'+$("#Date_1").val()+'/'+$("#Date_2").val(),
             success: function(data) {
                      $('#modal-cronograma').modal('toggle');
                      location.reload();
                }
        });
        
        
    }
    });

$(document).on('click', '#editar_contrato', function(e) {
    
    e.preventDefault();
    
    if ($('input[name=radio]:checked','.panel-body').val() == undefined){ alert ("Por favor seleccione el contrato que desea editar");}
    else{
        var datastring = $("#form_editar").serialize();
        $.ajax({
            type: "POST",
            url: globals.base_url + 'pacc/plan_de_adquisiciones/editar_contrato/'+$('input[name=radio]:checked','.panel-body').val(),
            data: datastring,
            dataType: "json",
            success: function(data) {
                location.reload();
            },
            error: function(xhr, status, error) {
            alert(xhr.responseText);
            }
        });
        
    }
    });    
    


$(document).on('click', '.fa-book', function(event) {
        $.ajax({
            type: "POST",
            url: globals.base_url + 'pacc/plan_de_adquisiciones/detalles_pagos/'+$('input[name=radio]:checked','.panel-body').val(),
            success: function(data) {
                     $('#modal-plan').replaceWith(data);
                     $('#modal-plan').modal();
                }
        });
    });
    
$(document).on('click', '.fa-info-circle', function(event) {
        $.ajax({
            type: "POST",
            url: globals.base_url + 'pacc/plan_de_adquisiciones/detalles_contrato/'+$('input[name=radio]:checked','.panel-body').val(),
            success: function(data) {
                      $('#modal-contrato').replaceWith(data);
                      $('#modal-contrato').modal();
                }
        });
    });    





$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

});//



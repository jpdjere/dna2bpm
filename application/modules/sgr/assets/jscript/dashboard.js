/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    /*RECTIFICA HREF*/
    $('[class^="rectifica-link"]').click(function(event) {    
        var parameter = $('.rectifica-link').attr('href');
        var arr = parameter.split('/');
        var input_period = arr[2];
        var anexo = arr[3];
        event.preventDefault();
        $.get(globals.module_url + "unset_period");

        $("input[name$='input_period']").val(input_period);
        $("input[name$='anexo']").val(anexo);
        $("#period").submit();

    });
    $('[class*="rectifica-warning"]').click(function(event) {   
        var parameter = $('.rectifica-warning').attr('href');
        alert(parameter);
        var arr = parameter.split('/');
        var input_period = arr[2];
        var anexo = arr[3];
        event.preventDefault();
        bootbox.confirm("El período " + input_period + " va a dejar de estar activo, desea continuar?", function(result) {
            if (result) {
                //$('#icon-calendar')[0].click();                
                    $("input[name$='input_period']").val(input_period);
                    $("input[name$='anexo']").val(anexo);
                    $("#period").submit();
                
            }
        });
    });


    $('.dp').datepicker();
    $("#others").hide();
    /*RECTIFICAR*/
    $('#rectify').change(function() {
        var option_value = $("#rectify option:selected").val();
        if (option_value == 3) {
            $("#others").show();
        } else {
            $("#others").hide();
        }
    });
//
    /*
     
     **/

    function add_no_movement() {
        var no_movement = $('#no_movement').val();
        var data = {'no_movement': no_movement};
        $.ajax(
                {
                    /* this option */
                    async: false,
                    cache: false,
                    type: "POST",
                    dataType: "text",
                    url: globals.module_url + 'set_no_movement',
                    data: {'data': data},
                    success: function(resp) {
                        var new_resp = (resp) ? "El periodo " + no_movement + " fue asociado con Exito" : "Error verifique la informacion";
                        bootbox.alert(new_resp, function() {
                            location.reload();
                        });
                    }
                });
    }

    $('button.no_movement').click(function() {
        var no_movement = $('#no_movement').val();
        bootbox.confirm("Confirma la asociacion del periodo " + no_movement + " como SIN MOVIMIENTO?", function(result) {
            if (result) {
                add_no_movement();
            }
        });
    });


    $('#dashboard_tab1 a:first').tab('show');
    $('.nav-tabs li a').click(function(e) {

        var code = $(this).attr('href').split('-');
        if ($(this).attr('href') == "#tab_resumen") {
            //$('.meta').hide();
            $('.ultree').show();
            $('#filtro_visitas').show();
        } else {

            $('.ultree').hide();
            $('#filtro_visitas').hide();
            $('[data-genia]').each(function(index) {
                var genia = $(this).attr('data-genia');
                if (genia != code[1]) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
                //  alert(genia+"/"+code[1]);
            });
        }


    });
//==== VISITAS ==== //

    $(document).on("click", ".ul_collapse", function() {
        $(this).next('UL').slideToggle();
    });
// Cargo visitas default
    $('#wrapper_visitas').load(globals.module_url + 'get_resumen_visitas');
// cambio el mes
    $('#dp4').datepicker().on('changeDate', function(ev) {
        var mes = ev.date.toISOString();
        $('#wrapper_visitas').load(globals.module_url + 'get_resumen_visitas', {'mes': mes});
    });
// Fin ready
});
function onUpdateReady() {
    // alert('found new version!');
    location.reload();
}
window.applicationCache.addEventListener('updateready', onUpdateReady);
if (window.applicationCache.status === window.applicationCache.UPDATEREADY) {
    onUpdateReady();
}

        
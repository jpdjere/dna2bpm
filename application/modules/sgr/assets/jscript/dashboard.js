/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {


    $('.dp').datepicker();
    $("#others").hide();


    /*RECTIFICAR*/
    $('#rectificar').change(function() {
        var option_value = $("#rectificar option:selected").val();
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
                        var new_resp = (resp) ? "OK" : "Error";
                        bootbox.alert(new_resp);
                    }
                });
    }

    $('button.no_movement').click(function() {
        var no_movement = $('#no_movement').val();
        bootbox.confirm("Are you sure ?" + no_movement, function(result) {
            
            alert("Confirm result: " + result);
        });


//        var no_movement = $('#no_movement').val();
//        var data = {'no_movement': no_movement};
//        $.ajax(
//                {
//                    /* this option */
//                    async: false,
//                    cache: false,
//                    type: "POST",
//                    dataType: "text",
//                    url: globals.module_url + 'set_no_movement',
//                    data: {'data': data},
//                    success: function(resp) {
//                        var new_resp = (resp) ? "OK" : "Error";
//                        bootbox.alert(new_resp);
//                    }
//                });
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

        
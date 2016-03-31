$(document).ready(function(e) {
    $('.search-panel .dropdown-menu').find('a').click(function(e) {
        e.preventDefault();
        var param = $(this).attr("href").replace("#", "");
        var param2 = $(this).attr("href").replace("#", "");

        var concept = $(this).text();
        $('.search-panel span#search_concept').text(concept);

        switch ($('#search_concept').text()) {

            case "Id de proyecto-Pacc11":
                type = 'id';
                programa = 'pacc11';
                break;
            case "Número de proyecto-Pacc11":
                type = 'ip';
                programa = 'pacc11';
                break;
            case "Id de proyecto-Pacc13":
                type = 'id';
                programa = 'pacc13';
                break;
            case "Número de proyecto-Pacc13":
                type = 'ip';
                programa = 'pacc13';
                break;
            case "CUIT-Pacc13":
                type = 'cuit';
                programa = 'pacc13';
                break;
            default:
                type = 'cuit';
                programa = 'pacc11';
        }
        $('.input-group #type').val(type);
        $('.input-group #programa').val(programa);
    });

    $('#buscador').click(function() {


        if ($('#search_concept').text() != "Buscar") {

            // var type;
            // var programa;

            // switch ($('#search_concept').text()) {

            //     case "Id de proyecto-Pacc11":
            //         type = 'id';
            //         programa = 'pacc11';
            //         break;
            //     case "Número de proyecto-Pacc11":
            //         type = 'ip';
            //         programa = 'pacc11';
            //         break;
            //     case "Id de proyecto-Pacc13":
            //         type = 'id';
            //         programa = 'pacc13';
            //         break;
            //     case "Número de proyecto-Pacc13":
            //         type = 'ip';
            //         programa = 'pacc13';
            //         break;
            //     case "CUIT-Pacc13":
            //         type = 'ip';
            //         programa = 'pacc13';
            //         break;
            //     default:
            //         type = 'cuit';
            //         programa = 'pacc11';
            // }
            // var data={
            //     'type':type,
            //     'programa':programa,
            //     'query':$('#query').val()
            // }
            // $.ajax({
            //     type: "POST",
            //     url: 'pacc/buscar_container/' + $('#valor').val() + "/" + type + "/" + programa,
            //     success: function(data) {
            //         $('#tabla-resumen').replaceWith(data);
            //     }
            // });

            // $('#tabla-resumen').show();

        }
        else {
            alert("Por favor, ingrese un criterio de búsqueda");
        }
    });
});
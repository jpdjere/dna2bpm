
$(document).ready(function(e) {
        $('.search-panel .dropdown-menu').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#", "");

            var concept = $(this).text();
            $('.search-panel span#search_concept').text(concept);
            $('.input-group #search_param').val(param);
        });
   
$(document).on("click", "#buscador", function() {

        if ($('#search_concept').text() != "Buscar por") {
            var type;
            switch ($('#search_concept').text()) {
                case "CUIT (sin guiones)":
                    type = 'cuit';
                    break;
                case "Razón Social":
                    type = 'razonsocial';
                    break;
            }
            $.ajax({
                type: "POST",
                url: 'tabla/' + $('#query').val() + "/" + type,
                success: function(data) {
                    $('#tabla').replaceWith(data);
                }
            });

            $('#tabla').show();

        }
        else {
            alert("Por favor, ingrese un criterio de búsqueda");
        }
    });
    

});
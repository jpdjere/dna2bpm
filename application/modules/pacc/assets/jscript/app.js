/**
 * dna2/inbox JS
 *
 **/
$(document).ready(function() {

    $('#MyTable').colorize();
    $('#t1').colorize();
    $('#t2').colorize();
    $('#t3').colorize();


    //====== fondyfpp_BO

    $(document).on('click', '.fondyfpp_BO', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var box = $(this).parents('.box-body');

        $.post(url, function(data) {
            $(box).html(data);
        });
    });

    $.fn.extend({
        treed: function(o) {

            var openedClass = 'glyphicon-minus-sign';
            var closedClass = 'glyphicon-plus-sign';

            if (typeof o != 'undefined') {
                if (typeof o.openedClass != 'undefined') {
                    openedClass = o.openedClass;
                }
                if (typeof o.closedClass != 'undefined') {
                    closedClass = o.closedClass;
                }
            };

            //initialize each of the top levels
            var tree = $(this);
            tree.addClass("tree");
            tree.find('li').has("ul").each(function() {
                var branch = $(this); //li with children ul
                branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
                branch.addClass('branch');
                branch.on('click', function(e) {
                    if (this == e.target) {
                        var icon = $(this).children('i:first');
                        icon.toggleClass(openedClass + " " + closedClass);
                        $(this).children().children().toggle();
                    }
                })
                branch.children().children().toggle();
            });
            //fire event from the dynamically added icon
            tree.find('.branch .indicator').each(function() {
                $(this).on('click', function() {
                    $(this).closest('li').click();
                });
            });
            //fire event to open branch if the li contains an anchor instead of text
            tree.find('.branch>a').each(function() {
                $(this).on('click', function(e) {
                    $(this).closest('li').click();
                    e.preventDefault();
                });
            });
            //fire event to open branch if the li contains a button instead of text
            tree.find('.branch>button').each(function() {
                $(this).on('click', function(e) {
                    $(this).closest('li').click();
                    e.preventDefault();
                });
            });
        }
    });

    //Initialization of treeviews
    tree_init();




    $(function() {
        /* BOOTSNIPP FULLSCREEN FIX */
        $('a[href="#modal-resumen"]').on('click', function(event) {
            event.preventDefault();
            $('#modal-resumen').modal('show');
        })

        $('a[href="#modal-eliminar"]').on('click', function(event) {
            event.preventDefault();
            $('#modal-eliminar').modal('show');
        });
    });


    $(document).ready(function(e) {
        $('.search-panel .dropdown-menu').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#", "");
            var param2 = $(this).attr("href").replace("#", "");

            var concept = $(this).text();
            $('.search-panel span#search_concept').text(concept);
            $('.input-group #search_param').val(param);
        });
    });


    $('#buscador').click(function() {


        if ($('#search_concept').text() != "Buscar") {

            var type;
            var program;

            switch ($('#search_concept').text()) {

                case "Id de proyecto-Pacc11":
                    type = 'id';
                    program = 'pacc11';
                    break;
                case "Número de proyecto-Pacc11":
                    type = 'ip';
                    program = 'pacc11';
                    break;
                case "Id de proyecto-Pacc13":
                    type = 'id';
                    program = 'pacc13';
                    break;
                case "Número de proyecto-Pacc13":
                    type = 'ip';
                    program = 'pacc13';
                    break;
                case "CUIT-Pacc13":
                    type = 'ip';
                    program = 'pacc13';
                    break;
                default:
                    type = 'cuit';
                    program = 'pacc11';
            }
            $.ajax({
                type: "POST",
                url: 'resumen_proyecto/tabla_reload/' + $('#query').val() + "/" + type + "/" + program,
                success: function(data) {
                    $('#tabla-resumen').replaceWith(data);
                }
            });

            $('#tabla-resumen').show();

        }
        else {
            alert("Por favor, ingrese un criterio de búsqueda");
        }
    });

    /* BOOTSNIPP FULLSCREEN FIX */
    $('a[href="#modal-nuevo-editar"]').on('click', function(event) {
        event.preventDefault();
        $('#modal-resumen').modal('show');
    });


    $(document).on('change', '#elemento', function(e) {
        $('#componente').toggle();
        $('#area-hidden').toggle();
    });

    $(document).on('change', '#elemento-eliminar', function(e) {
        $('#componente-eliminar').toggle();
        $('#subcomponente-eliminar').toggle();
    });
    
    $(document).on('change', '#elemento-editar', function(e) {
        $('#componente-editar').toggle();
        $('#subcomponente-editar').toggle();
    });



    $(document).on('click', '#boton-guardar', function(e) {
        $('#modal').modal('hide');

        $.ajax({
            type: "POST",
            url: globals.base_url + 'pacc/poa/cargar_elemento/' + $("#area").val() + '/' + $("#comp").val() + '/' + $("#codigo").val() + '/' + $("#descripcion").val() + '/' + $("#elemento").val(),
            success: function(data) {
                $('#replace').replaceWith(data);
                 tree_init();
            }
        });

    });

    $(document).on('click', '#boton-eliminar', function(e) {
        $.ajax({
            type: "POST",
            url: globals.base_url + 'pacc/poa/eliminar_elemento/' + $("#elemento-eliminar").val() + '/' + $("#compx").val() + '/' + $("#scompx").val(),
            success: function(data) {
                $('#replace').replaceWith(data);
                tree_init();
            }
        });

    });




}); //

function tree_init() {

  
        /* BOOTSNIPP FULLSCREEN FIX */
        $('a[href="#modal-resumen"]').on('click', function(event) {
            event.preventDefault();
            $('#modal-resumen').modal('show');
        });
        $('a[href="#modal-eliminar"]').on('click', function(event) {
            event.preventDefault();
            $('#modal-eliminar').modal('show');
        });
        $('a[href="#modal-editar"]').on('click', function(event) {
            event.preventDefault();
            $('#modal-editar').modal('show');
        });

    $('#1').treed({
        openedClass: 'glyphicon-folder-open',
        closedClass: 'glyphicon-folder-close'
    });
    $('#2').treed({
        openedClass: 'glyphicon-folder-open',
        closedClass: 'glyphicon-folder-close'
    });
    $('#3').treed({
        openedClass: 'glyphicon-folder-open',
        closedClass: 'glyphicon-folder-close'
    });
}

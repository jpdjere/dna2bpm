var last_div = 'nombre_id';
var first = true;
$(document).ready(function() {

    // Quick and dirty model for name and address.    
    function model(first, last, street, state, zip) {
        return {
            name: {
                first: first,
                last: last
            },
            address: {
                street: street,
                state: state,
                zip: zip
            }
        };
    }

    var $container = $('#nombre_id');

    // $container.handsontable({

    //     dataSchema: model,
    //     startRows: 4,
    //     startCols: 10,
    //     minSpareRows: 1,
    //     colHeaders: ['Indicadores', 'Unidad de medida', 'Val', 'Año', 'Val-1', 'Año-1', 'Val-2', 'Año-2', 'Fuente/ Medio de verificación', 'Observaciones'],
    //     rowaHeaders: false,
    //     manualColumnResize: true,

    //     afterRender: function() {
    //         $container.find('thead').find('tr').before(
    //             '<tr id="header-grouping">' +
    //             '<th></th>' +
    //             '<th></th>' +
    //             '<th colspan="2" valign="baseline">Línea de<br/>Base</th>' +
    //             '<th colspan="2">Mediciones<br/>intermedias</th>' +
    //             '<th colspan="2">Metas al <br/>final del<br/> proyecto</th>' +
    //             '<th></th>' +
    //             '<th></th>' +
    //             +'</tr>'
    //         );

    //     },

    //     beforeRender: function() {

    //         //            $('#header-grouping').remove();
    //     },
    //     modifyColWidth: function() {
    //         //$('#header-grouping').remove();
    //     }
    // });
    /**
     * Capturamos el click del botón
     */

    $('#button').click(function() {

        var div = $('#last_div');
        nuevo_id = last_div + 1;
        grupo=$('#grupo').val();
        // div.before('<h2>'+grupo+'</h2><div id="' + nuevo_id + '" class="productos"></div>');
        div.before('<div id="' + nuevo_id + '" class="productos"></div>');
        var container = $('#' + nuevo_id);

        container.handsontable({

            dataSchema: model,
            startRows: 4,
            startCols: 10,
            minSpareRows: 1,
            colHeaders: ['Indicadores', 'Unidad de medida', 'Val', 'Año', 'Val-1', 'Año-1', 'Val-2', 'Año-2', 'Fuente/ Medio de verificación', 'Observaciones'],
            rowaHeaders: ['Grupo'],
            manualColumnResize: true,
            mergeCells: [{
                row:0,
                col:0,
                colspan:10,
                rowspan:1
            }],
            
            
    
            afterRender: function() {
                if (true) {
                    container.find('thead').find('tr').before(
                        '<tr id="header-grouping">' +
                        '<th></th>' +
                        '<th></th>' +
                        '<th colspan="2" valign="baseline">Línea de<br/>Base</th>' +
                        '<th colspan="2">Mediciones<br/>intermedias</th>' +
                        '<th colspan="2">Metas al <br/>final del<br/> proyecto</th>' +
                        '<th></th>' +
                        '<th></th>' +
                        +'</tr>'
                    );
                }
            },

            beforeRender: function() {

                //$('#header-grouping').remove();
            },
            modifyColWidth: function() {
                //$('#header-grouping').remove();
            }
        });
        //----Pongo el nombre del grupo en la 1ra fila
        var hotInstance = $("#"+nuevo_id).handsontable('getInstance');
        hotInstance.setDataAtCell(0,0,grupo);
        //---Pongo la primera celda como read only
        // hotInstance.setCellMeta(0,0,'readOnly',true);
        //----inicializamos nueva tabla
        last_div = nuevo_id;
        first = false;
    });
    
    $('#button_h2').click(function() {

        var div = $('#last_div');
        nuevo_id = last_div + 1;
        grupo=$('#grupo').val();
        div.before('<h2>'+grupo+'</h2><div id="' + nuevo_id + '" class="productos"></div>');
        // div.before('<div id="' + nuevo_id + '" class="productos"></div>');
        var container = $('#' + nuevo_id);

        container.handsontable({

            dataSchema: model,
            startRows: 4,
            startCols: 10,
            minSpareRows: 1,
            colHeaders: ['Indicadores', 'Unidad de medida', 'Val', 'Año', 'Val-1', 'Año-1', 'Val-2', 'Año-2', 'Fuente/ Medio de verificación', 'Observaciones'],
            rowaHeaders: ['Grupo'],
            manualColumnResize: true,
    
            afterRender: function() {
                if (true) {
                    container.find('thead').find('tr').before(
                        '<tr id="header-grouping">' +
                        '<th></th>' +
                        '<th></th>' +
                        '<th colspan="2" valign="baseline">Línea de<br/>Base</th>' +
                        '<th colspan="2">Mediciones<br/>intermedias</th>' +
                        '<th colspan="2">Metas al <br/>final del<br/> proyecto</th>' +
                        '<th></th>' +
                        '<th></th>' +
                        +'</tr>'
                    );
                }
            },

            beforeRender: function() {

                //$('#header-grouping').remove();
            },
            modifyColWidth: function() {
                //$('#header-grouping').remove();
            }
        });
        //----Pongo el nombre del grupo en la 1ra fila
        var hotInstance = $("#"+nuevo_id).handsontable('getInstance');
        //hotInstance.setDataAtCell(0,0,grupo);
        //---Pongo la primera celda como read only
        // hotInstance.setCellMeta(0,0,'readOnly',true);
        //----inicializamos nueva tabla
        last_div = nuevo_id;
        first = false;
    });



});
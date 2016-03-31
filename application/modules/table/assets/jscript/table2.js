var last_div = 'nombre_id';
var first = true;
var hotInstance={};
var last_edited=[];
var data=[
    ["a","a","a",null,null,null,null,null,null,null],
    ["a","a","a",null,null,null,null,null,null,null],
    [null,null,null,null,null,null,null,null,null,null],
    [null,null,null,null,null,null,null,null,null,null],
    [null,null,null,null,null,null,null,null,null,null],
    [null,null,null,null,null,null,null,null,null,null],
    [null,null,null,null,null,null,null,null,null,null]
    ]
$(document).ready(function() {
var greenRenderer = function (instance, td, row, col, prop, value, cellProperties) {
  Handsontable.renderers.TextRenderer.apply(this, arguments);
  if(cellProperties.is_group)
  td.style.backgroundColor = 'DarkSeaGreen';

};
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
    
    var container = $('#last_div');
    container.handsontable({
        dataSchema: model,
        startRows: 4,
        startCols: 10,
        minSpareRows: 1,
        colHeaders: ['Indicadores', 'Unidad de medida', 'Val', 'Año', 'Val-1', 'Año-1', 'Val-2', 'Año-2', 'Fuente/ Medio de verificación', 'Observaciones'],
        rowaHeaders: false,
        manualColumnResize: true,
        contextMenu:true,
        mergeCells:[{
                row:0,
                col:0,
                colspan:10,
                rowspan:1
            }],
        cells: function (row, col, prop) {
            // props=this.instance.getCellMeta(row,col);
            // console.log(row,col, this.instance.getCellMeta(row,col));
            // if (props.is_group) {
            this.renderer = greenRenderer;
            // }
        },
        afterRender: function() {
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

        },

        beforeRender: function() {
            // console.log(this.instance);
            // this.selection.setRangeStart({col:0,row:0});
            // this.selection.setRangeEnd({col:9,row:0});
            // this.mergeCells.mergeSelection(hotInstance.getSelectedRange());
            //            $('#header-grouping').remove();
        },
        modifyColWidth: function() {
            //$('#header-grouping').remove();
        },
        afterOnCellMouseDown:function (event, coords, TD){
            last_edited=coords;
        }
    });
    hotInstance = $("#last_div").handsontable('getInstance');
    /**
     * Capturamos el click del botón
     */

    $('#button').click(function() {
        // var sel=hotInstance.getSelected();
        var row=last_edited.row;
        var col=last_edited.col;
        grupo=$('#grupo').val();
        hotInstance.setCellMeta(row,0,'is_group',true);
        hotInstance.setDataAtCell(row,0,grupo);
        hotInstance.selection.setRangeStart({col:0,row:row});
        hotInstance.selection.setRangeEnd({col:9,row:row});
        hotInstance.mergeCells.mergeSelection(hotInstance.getSelectedRange());
        hotInstance.render();
        // hotInstance.updateSettings({mergeCells: [{
        //         row:row,
        //         col:0,
        //         colspan:10,
        //         rowspan:1
        //     }]});
        // div.before('<h2>'+grupo+'</h2><div id="' + nuevo_id + '" class="productos"></div>');

    });


});
var geniaVinculadas = new Ext.util.Filter({
    filterFn: function(item) {
        return item.get('origenGenia');
    }
});
var geniaNoVinculadas = new Ext.util.Filter({
    filterFn: function(item) {
        return !item.get('origenGenia');
    }
});
var btnVinculadas = Ext.create('Ext.Action',
        {
            id: 'btnVinculadas',
            text: 'Vinculadas',
            iconCls: 'icon icon-check-sign',
            tooltip: 'Ver Vinculadas',
            handler: function() {
                EmpresaStore.clearFilter();
                EmpresaStore.filter(geniaVinculadas);
            }
        });
var btnTodas = Ext.create('Ext.Action',
        {
            id: 'btnTodas',
            text: 'Todas',
            iconCls: '',
            tooltip: 'Borrar Filtro',
            handler: function() {
                EmpresaStore.clearFilter();
            }
        });
var btnNoVinculadas = Ext.create('Ext.Action',
        {
            id: 'btnNoVinculadas',
            text: 'No Vinculadas',
            iconCls: 'icon icon-check-empty',
            tooltip: 'ver empresas no Vinculadas',
            handler: function() {
                EmpresaStore.clearFilter();
                EmpresaStore.filter(geniaNoVinculadas);
            }
        });
var btnAdd = Ext.create('Ext.Action',
        {
            id: 'btnAdd',
            text: 'Agregar',
            iconCls: 'icon icon-plus',
            tooltip: 'Agregar',
            handler: function() {
                window.location = globals.module_url + 'form_empresas_alt';
            }
        });

function gridClick(view, record, item, index, e, options) {
    cuit = record.data['1695'];
    window.open(globals.module_url + 'form_empresas_alt?cuit=' + cuit, '_blank');
    return;
}

//var EmpresasGrid=Ext.create('Ext.grid.Panel',{
var EmpresasGrid = Ext.create('Ext.ux.LiveFilterGridPanel', {
    //title:'All Similar Frames Available',
    stripeRows: true,
    indexes: ['1693', '1695'],
    layout: 'fit',
    columnLines: true,
    autoScroll: true,
    scroll: 'vertical',
    id: 'EmpresasGrid',
    store: EmpresaStore,
    columns: [
        /*
         {
         menuDisabled: true,
         sortable: false,
         xtype: 'actioncolumn',
         width: 50,
         items: [{
         icon   : globals.module_url+'assets/images/delete.png',  // Use a URL in the icon config
         tooltip: 'Remove case from DB',
         handler: function(grid, rowIndex, colIndex) {
         var rec = dgstore.getAt(rowIndex);
         Ext.Msg.confirm('Confirm', 'Are you sure you want to remove: '+rec.get('id')+'?',confirm,rec);
         
         }
         }]
         },
         */
        Ext.create('Ext.grid.RowNumberer'),
        {
            flex: 1,
            text: "CUIT",
            dataIndex: '1695',
            sortable: true

        },
        {
            flex: 2,
            text: "Nombre / Raz&oacute;n Social",
            dataIndex: '1693',
            sortable: true

        },
        {
            flex: 1,
            text: "Partido",
            dataIndex: 'partido_txt',
            sortable: true

        },
        {
            dataIndex: '7819',
            text: '<i class="icon-map-marker"></i> Geo',
            align: 'center',
            renderer: function(val) {
                if (val) {
                    rtn = '<i class="icon-map-marker"></i>';
                } else {
                    rtn = '<i class="icon-map-marker icon-muted"></i>';
                }
                return rtn;
            }
        }, {
            text: 'Vinculada',
            align: 'center',
            dataIndex: 'origenGenia',
            renderer: function(val) {
                if (val) {
                    rtn = '<i class="icon-check-sign"></i>';
                } else {
                    rtn = '';
                }
                return rtn;
            }

        }
        ,
    ],
    stripeRows       : true,
            plugins: {
        ptype: 'bufferedrenderer',
        trailingBufferZone: 20, // Keep 20 rows rendered in the table behind scroll
        leadingBufferZone: 50   // Keep 50 rows rendered in the table ahead of scroll
    },
    ////////////////////////////////////////////////////////////////////////////
    //////////////////////   LISTENERS  ////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    listeners: {
        /*
         selectionchange: function( me, selected, eOpts ){
         load_model(globals.idwf);
         },*/
        itemclick: gridClick
    }
    ////////////////////////////////////////////////////////////////////////////
    //////////////////////   DOCKERS    ////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    
    /*,tbar: [
        {
            xtype: 'button',
            text: '<i class="icon-repeat"></i>',
            handler: function() {
                mygrid.store.read();
            }
        },
        btnVinculadas,
        btnAdd,
                '->',
        btnNoVinculadas,
        , btnSync
    ]*/

});
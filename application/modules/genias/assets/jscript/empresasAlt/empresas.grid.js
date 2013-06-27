var btnSync=Ext.create('Ext.Action',
    {
        id:'btnSync',
        text: 'Sync',
        iconCls:'icon icon-cloud-upload',
        tooltip:'Sincronizar cambios',
        handler:function(){
            var records = new Array();
            //---me guardo el proxy offline
            offlineProxy=storeEmpresaOffline.proxy;
            //---le pongo el proxy AJAX                   
            //---Marcamos Dirty cada uno de los registros
            storeEmpresaOffline.each(function(rec) {
                rec.setDirty();
                store.add(rec)
            });
            store.sync();
        }
    }    
    );
        
function gridClick (view,record,item,index,e,options ){
    cuit=record.data['1695'];
    EmpresaForm.setLoading('Cargando...');
    EmpresaForm.loadRecord(record);
    EmpresaForm.setLoading(false);
    //url=globals.module_url+'case_manager/tokens/status/'+globals.idwf+'/'+thisCase;    
    //console.log(cuit);
    VisitasStore.cuitFilter(cuit);
    return;
}

var EmpresasGrid=Ext.create('Ext.ux.LiveFilterGridPanel',{
    //title:'All Similar Frames Available',
    stripeRows : true,
    indexes:['1693','1695'],
    columnLines: true,
    id:'EmpresasGrid',
    store:EmpresaStore,    
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
        flex:1,
        text: "CUIT",
        dataIndex: '1695',
        sortable: true
           
    },
    {
        flex:3,
        text: "Nombre / Raz&oacute;n Social",
        dataIndex: '1693',
        sortable: true
           
    }
    ,
    ],
    stripeRows       : true,
    ////////////////////////////////////////////////////////////////////////////
    //////////////////////   LISTENERS  ////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    listeners: {
        /*
        selectionchange: function( me, selected, eOpts ){
            load_model(globals.idwf);
        },*/
        itemclick: gridClick
    },
    
    ////////////////////////////////////////////////////////////////////////////
    //////////////////////   DOCKERS    ////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    tbar: [
    {
        xtype: 'button', 
        text: '<i class="icon-repeat"></i>',
        handler:function(){    
            mygrid.store.read();
        }
    }               
    ,btnSync
    ]    

});

var notaTpl = new Ext.XTemplate(
    '<tpl for=".">',
    '<div class="img-polaroid">',
    '<span class="fecha label label-success">{fecha}</span>',
    '<h5>{nota}</h5>',
    '</div>',
    '</tpl>'
    );
var VisitasGrid=Ext.create('Ext.view.View',
{
    id:'VisitasGrid',
    store:VisitasStore,    
    tpl:notaTpl,
    itemSelector: 'span.fecha'
    
});
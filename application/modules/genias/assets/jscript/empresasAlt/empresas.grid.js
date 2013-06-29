
function gridClick (view,record,item,index,e,options ){
    cuit=record.data['1695'];
    EmpresaForm.loadRecord(record);    
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

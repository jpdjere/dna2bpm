
var btnAdd = Ext.create('Ext.Action',
{
    id: 'btnAdd',
    text: 'Agregar',
    iconCls: 'icon icon-plus',
    tooltip: 'Agregar',
    handler: function() {
        window.location=globals.module_url+'form_empresas_alt';
    }
});

function gridClick (view,record,item,index,e,options ){
    cuit=record.data['1695'];
    window.open(globals.module_url+'form_empresas_alt?cuit='+cuit,'_blank');
    return;
}

//var EmpresasGrid=Ext.create('Ext.grid.Panel',{
    var EmpresasGrid=Ext.create('Ext.ux.LiveFilterGridPanel',{
    //title:'All Similar Frames Available',
    stripeRows : true,
    indexes:['1693','1695'],
    layout:'fit',
    columnLines: true,
    autoScroll:true,
    scroll:'vertical',
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
        flex:2,
        text: "Nombre / Raz&oacute;n Social",
        dataIndex: '1693',
        sortable: true

    },
    {
        flex:1,
        text: "Partido",
        dataIndex:'partido_txt',
        sortable:true
        
    }
    ,
    ],
    stripeRows       : true,
    plugins: {
        ptype: 'bufferedrenderer',
        trailingBufferZone: 20,  // Keep 20 rows rendered in the table behind scroll
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
    },
    btnAdd,'->',               
    ,btnSync
    ]    

});
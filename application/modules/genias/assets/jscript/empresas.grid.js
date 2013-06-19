function gridClick (view,record,item,index,e,options ){
    thisEmpresa=record.data['1695'];
    EmpresaForm.loadRecord(record);
    //url=globals.module_url+'case_manager/tokens/status/'+globals.idwf+'/'+thisCase;    
    console.log(thisEmpresa);
    return;
    tokenStore=Ext.getStore('tokenStore')
    tokenStore.proxy.url=url;
    tokenStore.load({
        scope: this,
        callback:tokens_paint_all    
    });
    load_model(globals.idwf);
    Ext.getCmp('ModelPanelTbar').enable();
    gridIndex=0;
}

var EmpresasGrid=Ext.create('Ext.grid.Panel',
{
    columnLines: true,
    id:'centerGrid',
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
        flex:1,
        text: "Nombre / Raz&oacute;n Social",
        dataIndex: '1693',
        sortable: true
           
    }
    ,
    {
        flex:1,
        text: "Status",
        dataIndex: 'status',
        sortable: true,
        renderer: function(value){
            switch(value){
                case 'dirty':
                    stClass='label-warning';
                    break;
                case 'ok':
                    stClass='label-success';
                    break;
                case 'locked':
                    stClass='';
                    
                    break;
                default:
                    stClass='label-success';
                    break;
                
            }
                
            value='<span class="label '+stClass+'">'+value+'</span>'
            return value;
        }
    }
    //,checkLock
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
        fieldLabel: 'Search',
        labelWidth: 50,
        xtype: 'searchfield',
        store: EmpresaStore
    },
    {
        xtype: 'button', 
        text: '<i class="icon-repeat"></i>',
        handler:function(){    
            mygrid.store.read();
        }
    }               
   
    ]    

});
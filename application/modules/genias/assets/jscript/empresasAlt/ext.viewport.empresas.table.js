Ext.onReady(function() {
    var remove_loaders = function() {
        Ext.get('loading').remove();
        Ext.fly('loading-mask').remove();
    }
    Ext.create('Ext.Viewport', 
    {
        id:'main-panel',        
        layout:'fit',
        items:[
        {
            title:"Listado de Empresas",   
            layout:"fit",
            tbar:[
            {
                xtype: 'button', 
                text: '<i class="icon-repeat"></i>',
                handler:function(){    
                    mygrid.store.read();
                }
            },
            btnAdd,'->',               
            ,btnSync
            ], 
            items:[EmpresasGrid]
        }
        ],
        listeners: {
            render: function() {

            },
            afterRender: function() {
                remove_loaders();
            }

        }
    });
});
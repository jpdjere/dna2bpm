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
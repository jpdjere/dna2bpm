Ext.onReady(function() {
    var remove_loaders = function() {
        Ext.get('loading').remove();
        Ext.fly('loading-mask').remove();
    }
    Ext.create('Ext.Viewport',{
        id:'main-panel',
        baseCls:'x-plain',
        renderTo: Ext.getBody(),
        layout:'fit',
        items:[
        {
            layout: {
                type: 'table',
                columns: 2
            },
            // applied to child components
            defaults: {
                frame:false, 
                width:400, 
                height:400
            },
            items:[{
                title:'Item 1',
                height:760,
                width:580,
                autoScroll:true,
                items:[EmpresasGrid]
            },{
                title:'Item 2',
                height:760,
                items:[
                    {                        
                    layout: {
                        // layout-specific configs go here
                        type: 'accordion',
                        titleCollapse: true,
                        animate: false,
                        activeOnTop: false
                    },
                    items: [{
                        title: 'Panel 1',
                        items:[EmpresaForm]
                    },{
                        title: 'Panel 2',
                        items:[VisitasGrid]
                    }]
                }
                ]

            }],
            listeners: {
                render: function() {

                },
                afterRender: function() {
                    remove_loaders();
                }

            }
        }]
    });
});

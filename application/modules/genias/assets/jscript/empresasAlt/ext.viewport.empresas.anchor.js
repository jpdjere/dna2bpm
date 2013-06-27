Ext.onReady(function() {
    var remove_loaders = function() {
        Ext.get('loading').remove();
        Ext.fly('loading-mask').remove();
    }
    Ext.create('Ext.Viewport', 
    {
        layout:'column',
        autoScroll:true,
        defaults: {
            layout: 'anchor',
            defaults: {
                anchor: '90%'
            }
        },
        items: [{
            columnWidth: 2/3,
            items:[{
                title: 'A Panel',
                autoScroll:true,
                anchor:'-80 50%',
                items:[EmpresasGrid]
            }]
        },{
            columnWidth: 1/3,
            items:[{
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

            }]
        }],
        listeners: {
            render: function() {

            },
            afterRender: function() {
                remove_loaders();
            }

        }
    });
//----end viewport
});

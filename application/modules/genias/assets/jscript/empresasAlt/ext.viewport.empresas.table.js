Ext.onReady(function() {
    var remove_loaders = function() {
        Ext.get('loading').remove();
        Ext.fly('loading-mask').remove();
    }
    Ext.create('Ext.panel.Panel',{
        id:'main-panel',
        renderTo: Ext.getBody(),
        autoScroll:true,
        
        height:1280,
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
                height:1280,
                width:580,
                autoScroll:true,
                items:[EmpresasGrid]
            },{
                height:1280,
                items:[
                {
                    height:500,
                    autoScroll:true,
                    title: 'Datos',
                    items:[EmpresaForm]
                },{
                    //                    height:396,
                    autoScroll:true,
                    title: 'Visitas',
                    height:400,
                    items:[VisitasGrid]
                }]
                

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

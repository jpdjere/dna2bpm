Ext.application({
    name: 'FormGenias',
    init: function() {

    },
    launch: function() {

        var remove_loaders = function() {
            Ext.get('loading').remove();
            Ext.fly('loading-mask').remove();
        }
        var onlineMode = (navigator.onLine) ? true : false;            
        var mode=(onlineMode) ? '<i class="icon icon-circle"></i> On-Line':'<i class="icon icon-ban-circle"></i> Off-Line';
        var center = Ext.create('Ext.Panel', 
        {
            title:'Empresas: '+mode,
            region:'center',
            margins:'0 0 0 0',
            layout:'border',
            items: [
            {
                region:'center',
                layout:'fit',
                //html:'<h1>GRID EMPRESAS</h1>'
            items:[EmpresasGrid]
            }
            ]
        }
        );
        
        var right = Ext.create('Ext.Panel', 
        {
            region:'east',
            margins:'0 0 0 0',
            width: 500,
            minWidth: 400,
            maxWidth: 700,
            margins: '0 0 0 0',
            layout:'border',
            animCollapse: true,
            collapsible: true,
            animCollapse: false,
            split: true,
            title: '<i class="icon icon-info-sign"></i> Datos de la Empresa',
            items: [
            {
                region:'center',
                layout:'fit',
            //    html:'<h1>FORM EMPRESA</h1>'
            items:[EmpresaForm]
            }
            ,
            {
                title: '<i class="icon icon-time"></i> Historial Visitas / Notas',
                region:'south',
                layout:'fit',
                collapsible: true,
                collapsed:true,
                animCollapse: false,
                resizable:true
                ,
                split: true,
                height:300,
                html:'<h1>HISTORIAL VISITAS</h1>'
            //items:[gridVisitas]
            }
            ]
        }
        );

        //---CREATE VIEWPORT  

        Ext.create('Ext.Viewport', {
            layout: 'border',
            items: [
            center,
            right
            ],
            listeners: {
                render: function() {
                    
                },
                afterRender: function() {
                    remove_loaders();
                }

            }
        });
    }

});


Ext.create('Ext.Panel', 
    {
        title:'Empresas: '+mode,
        region:'center',
        margins:'0 0 0 0',
        layout:'fit',
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
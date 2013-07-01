Ext.onReady(function() {
    var onlineMode = (navigator.onLine) ? true : false;            
    var mode=(onlineMode) ? '<i class="icon icon-circle"></i> On-Line':'<i class="icon icon-ban-circle"></i> Off-Line';
    
    if (onlineMode) {
    storeEmpresaOffline.load();
    Ext.getCmp('btnSync').setText('Hay (' + storeEmpresaOffline.getCount() + ') para actualizar');
    } else {
        /*Si no esta Online no puede sincronizar*/
        Ext.getCmp('btnSync').hide();
    }
    
    /* Para tareas relacionadas via Agenda*/
    var getParams = document.URL.split("/");
    var params = (getParams[getParams.length - 1]);
    Ext.getCmp('task').setValue(params);
    
    
    var remove_loaders = function() {
        Ext.get('loading').remove();
        Ext.fly('loading-mask').remove();
    }
    title='<div class="row"><div class="span8">'+mode+'</div><div><p class="text-right"><a class=.navbar-inverse style="color:#FFFF;" href="'+globals.module_url+'">Volver <i class="icon-chevron-sign-right"></i></a></p></div></div>';
    //Ext.create('Ext.panel.Panel',{
    Ext.create('Ext.Viewport', {
        id:'main-panel',
        autoScroll:true,
        layout:'fit',
        items:[
        {
            title:title,
            layout:'column',
            autoScroll:true,
            defaults: {
                layout: 'anchor',
                defaults: {
                    anchor: '100%'
                }
            },
            items: [{
                columnWidth: 1/3,
                baseCls:'x-plain',
                bodyStyle:'padding:5px 0 5px 5px',
                items:[{
                    title: 'Datos Empresa',
                    items:[EmpresaForm]
                }]
            },{
                columnWidth: 2/3,
                baseCls:'x-plain',
                bodyStyle:'padding:5px 0 5px 5px',
                items:[{
                    title: 'Visitas',
                    items:[VisitasGrid]
                }]
            }]
        }]
        ,
        listeners: {
            render: function() {

            },
            afterRender: function() {
                remove_loaders();
            }

        }
    });
});

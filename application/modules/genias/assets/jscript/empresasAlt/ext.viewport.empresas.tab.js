Ext.onReady(function() {
    var onlineMode = (navigator.onLine) ? true : false;
    var mode = (onlineMode) ? '<div id="status"><i class="icon icon-circle"></i> On-Line' : '<i class="icon icon-off"></i> Off-Line...</div>';

    if (onlineMode) {
        storeEmpresaOffline.load();

        var getCount = storeVisitaOffline.getCount() + storeEmpresaOffline.getCount();
        Ext.getCmp('btnSync').setText('Hay (' + getCount + ') para actualizar');

    } else {
        /*Si no esta Online no puede sincronizar*/
        Ext.getCmp('btnSync').hide();
    }


    Ext.require('Ext.tab.*');
    var tabs = new Ext.TabPanel({
        activeTab: 0,
        items: [{
                title: 'Empresa',
                items: [EmpresaForm]
            }, {
                title: 'Seguimiento ',
                items: [
                    {
                        layout: 'column',
                        autoScroll: true,
                        items: [{
                                columnWidth: 1 / 2,
                                baseCls: 'x-plain',
                               // bodyStyle: 'padding:0 0 5px 5px',
                                items: [{
                                        title: 'Datos Visitas',
                                        items: [VisitaForm]
                                    }]
                            }, {
                                columnWidth: 1 / 2,
                                baseCls: 'x-plain',
                                bodyStyle: 'padding:0 0 0px 5px',
                                items: [{
                                        title: 'Histórico Visitas',
                                        items: [VisitasGrid]
                                    }]
                            }]
                    }]
            }],
        defaults: {
            autoScroll: false,
            //layout: 'form', // tried with and without
            deferredRender: false   // likewise
        }
    }
    );

    /* Para tareas relacionadas via Agenda*/
    var getParams = document.URL.split("/");
    var params = (getParams[getParams.length - 1]);
    Ext.getCmp('task').setValue(params);


    var remove_loaders = function() {
        Ext.get('loading').remove();
        Ext.fly('loading-mask').remove();
    }
    //Ext.create('Ext.panel.Panel',{
    Ext.create('Ext.Viewport', {
        id: 'main-panel',
        autoScroll: true,
        layout: 'fit',        
        items: [
            {
                /*title:title,*/
                title: '\
            <div class="navbar navbar-inverse navbar-static-top "> \
                <div class="navbar-inner barra_user">\
                    <ul class="nav pull-left inline"><li> \
                        <a href="#"">' + mode + '</a>\
                        </li></ul>\
                        <ul class="nav pull-right inline"><li><a href="' + globals.module_url + '">Volver <i class="icon-chevron-sign-right icon2x"></i></a>\
                        </li></ul>\
                </div>\
            </div>',
                layout: 'fit',                
                defaults: {                    
                    layout: 'anchor',
                    defaults: {                        
                        anchor: '100%'
                    }
                },
                items: [{
                        layout: 'fit',
                        baseCls: 'x-plain',
                        items: [tabs]
                    }]
            }]
                ,
        listeners: {
            render: function() {

            },
            afterRender: function() {
                remove_loaders();
                loaded();
            }

        }
    });
});



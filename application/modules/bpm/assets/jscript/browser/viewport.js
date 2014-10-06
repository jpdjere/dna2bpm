Ext.onReady(function(){
    //---define components
    var left=Ext.create('Ext.Panel',
    {
        region: 'west',
        id: 'leftPanel', // see Ext.getCmp() below
        title: 'Model Tree',
        //                            title: 'West',
        split: true,
        width: 360,
        minWidth: 300,
        maxWidth: 700,
        collapsible: true,
        animCollapse: true,
        margins: '0 0 0 0',
        layout: 'fit',
        items:[tree]
    }
    );
    var right=Ext.create('Ext.panel.Panel',
    { 
        title: 'Model Properties',
        region:'east',
        id: 'rightPanel',
        layout: 'fit',
        margins: '0 0 0 0',
        split: true,
        width: 360,
        minWidth: 300,
        maxWidth: 700,
        collapsed: false,
        collapsible: true,
        animCollapse: true,
        margins: '0 0 0 0',
        layout: 'fit',
        items:[propsGrid]
    });
    var center=Ext.create('Ext.panel.Panel',
    { 
        region:'center',
        id: 'centerPanel',
        layout: 'fit',
        margins: '0 0 0 0',
        items: [center_panel]
    });
    //---Create Application
    Ext.application({
        name: 'Model admin',
        launch: function() {
            Ext.create('Ext.container.Viewport', {
                layout:'border',
                items:[ 
                /*
                {
                    region:'north',
                    title:'<h3 class="hidden-tablet hidden-phone"><i class="icon icon-bpm"></i> BPM admin</h3>',
                    cls:'page_header',
                    collapsible:true
                },
                */
                left,
                center,
                right
                ],
                listeners: {

                    afterrender: function(){
                        //---Load Data

                        //Ext.data.StoreManager.lookup('GroupStore').load(); 
                        //Ext.data.StoreManager.lookup('UserStore').load(); 
                        Ext.data.StoreManager.lookup('TreeStore').load(); 


                    }
                }
            });

        },
        onLaunch: function(){
        }
    });
    //---remove the loader
    Ext.get('loading').remove();
    Ext.fly('loading-mask').remove();


});
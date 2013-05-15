Ext.onReady(function(){
    //---define components
    var left=Ext.create('Ext.Panel',
    {
        region: 'west',
        id: 'leftPanel', // see Ext.getCmp() below
        title: 'File Tree',
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
    var center=Ext.create('Ext.Panel',
    { 
        region:'center',
        id: 'centerPanel',
        layout: 'fit',
        margins: '0 0 0 0',
        autoScroll:true,
        items: []
    });
    //---Create Application
    Ext.application({
        name: 'FileMan',
        launch: function() {
            Ext.create('Ext.container.Viewport', {
                layout:'border',
                items:[ {
                    region:'north',
                    title:'<h3><i class="icon icon-file"></i> FileMan</h3>',
                    cls:'page_header'
                },
                left,
                center
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
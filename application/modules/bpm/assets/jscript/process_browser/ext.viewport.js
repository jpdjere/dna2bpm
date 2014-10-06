var modelPanel = Ext.create('Ext.panel.Panel', {
    id: 'modelPanel',
    autoScroll: true,
    listeners: {
//  render: load_model
    }
});

Ext.application({
    name: 'AppEditor',
    init: function () {

    },
    launch: function () {
        var remove_loaders = function () {

            Ext.get('loading').remove();
            Ext.fly('loading-mask').remove();

        }
        var panel = Ext.create('Ext.panel.Panel',
                {
                    xtype: 'panel',
                    id: 'info-panel',
                    layout: 'fit',
                    overflowY: 'scroll',
                    margins: '5 0 5 5',
                    html:'<div id="load-content"></div>'
                }
        );
        var right = Ext.create('Ext.panel.Panel',
                {
                    title: '<i class="fa fa-info-circle"></i>',
                    region: 'east', // position for region
                    id: 'chat-panel',
                    xtype: 'panel',
                    width: 400,
                    split: true, // enable resizing
                    collapsible: true, // make collapsible
                    collapsed: false,
                    layout: 'fit',
                    items: [panel]
                });
        var center = Ext.create('Ext.Panel',
                {
                    region: 'center',
                    margins: '0 0 0 0',
                    layout: 'border',
                    items: [
//            {
//                region:'south',
//                layout:'fit',
//                title:'<i class="icon icon-time" ></i> Token History',
//                collapsible: true,
//                collapsed:true,
//                resizable:true,
//                height:300,
//                items:[tokenGrid]
//            }
//            ,
                        {
                            //title: '<i class="icon icon-bpm"></i> Model Panel / Picker',
                            title: '<i class="icon icon-info-sign"></i> Process Browser',
                            id: 'ModelPanel',
                            region: 'center',
                            layout: 'fit',
                            collapsible: false,
                            collapsed: false,
                            animCollapse: false,
                            resizable: true,
                            split: true,
                            items: [modelPanel],
                            tbar: {
                                id: 'ModelPanelTbar',
                                disabled: true,
//                    items:[
//                    TokensPlay,
//                    TokensStop,
//                    TokensStepBackward,
//                    TokensStepForward,
//                    TokensTimeSlider,
//                    TokensFolow,
//                    TokensShowExtras,
//                    TokensReload,
//                    TokensStatus,
//                    TokensHistory,
//                    ]
                            }
                        }
                    ]
                }
        );

        //---CREATE TOKENS VIEWPORT  

        Ext.create('Ext.Viewport', {
            layout: 'border',
            id:'border-panel',
            items: [
                center, right
            ]
            ,
            listeners: {
                render: function () {
                },
                afterRender: function () {
                    remove_loaders();
//                    load_data_callback=tokens_paint_all;
                    load_model(globals.idwf);
//                    tokens_load_status(globals.idwf,globals.idcase);
                }

            }
        });
    }

});


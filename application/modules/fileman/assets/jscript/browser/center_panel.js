/*
 * Center panel
 * 
 */

var ModelEdit = Ext.create('Ext.Action', {
    text:'Edit',
    iconCls:' icon-edit',
    handler:function (){
        TreeDblClick(tree,null);
    }
});

var ModelImport= Ext.create('Ext.Action', {
    text:'Import',
    iconCls:'icon icon-download-alt',
    handler:function (){
        Ext.create('Ext.window.Window', {
            title: '<h5><i class="icon icon-download-alt"></i> Import Model</h5>',
            id:Ext.id(),
            height: 200,
            width: 500,
            layout: 'fit',
            items:[
            Ext.create('Ext.form.Panel', {
                width: 400,
                bodyPadding: 10,
                
                items: [{
                    xtype: 'fileuploadfield',
                    name: 'file',
                    fieldLabel: 'file',
                    labelWidth: 50,
                    msgTarget: 'side',
                    allowBlank: false,
                    anchor: '100%'
                }],

                buttons: [{
                    text: 'Upload',
                    handler: function() {
                        var form = this.up('form').getForm();
                        if(form.isValid()){
                            form.submit({
                                url: globals.module_url+'repository/import/model',
                                waitMsg: 'Uploading your file...',
                                success: function(fp, o) {
                                    Ext.Msg.alert('Status',o.result.msg);
                                }
                                ,
                                failure: function(form, action) {
                                    switch (action.failureType) {
                                        case Ext.form.action.Action.CLIENT_INVALID:
                                            Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
                                            break;
                                        case Ext.form.action.Action.CONNECT_FAILURE:
                                            Ext.Msg.alert('Failure', 'Ajax communication failed');
                                            break;
                                        case Ext.form.action.Action.SERVER_INVALID:
                                            Ext.Msg.alert('Failure', action.result.msg);
                                    }
                                }
                            });//----end form submit
                        }
                    }
                }]
            })//----formpanel
            ]//end items
        }).show();//--end create
    }//---end handler
});

var ModelExport= Ext.create('Ext.Action', {
    text:'Export',
    iconCls:'icon icon-upload-alt',
    handler:function (){
        var body = Ext.getBody(),
        n=tree.getSelectionModel().getSelection()[0];
        //---only do something if its leaf=model
        if(n && n.isLeaf()){
            frame = body.createChild({
                src:globals.base_url+'images/zip/'+n.data.id+'.zip',
                tag:'iframe',
                cls:'x-hidden',
                id:'hiddenform-iframe',
                name:'iframe'
            });
        } else {
            //---show message
            Ext.MessageBox.alert('Error!', "Select a model to export");
        }
        
    }
});

var ModelCloudExport= Ext.create('Ext.Action', {
    text:'Export',
    iconCls:'icon icon-cloud-upload',
    handler:function (){
        
    }
});

var ModelCloudImport= Ext.create('Ext.Action', {
    text:'Import',
    iconCls:'icon icon-cloud-download',
    handler:function (){}
});

var ModelView = Ext.create('Ext.Action', {
    text:'View',
    iconCls:'icon-file',
    handler:function(){
        n=tree.getSelectionModel().getSelection()[0];
        //---only do something if its leaf=model
        
        if(n && n.isLeaf()){
            url= globals.module_url+'repository/view/model/'+n.data.id;
            window.open(url);
        }
    }
});
var ModelDump = Ext.create('Ext.Action', {
    text:'Dump',
    iconCls:'icon-circle-arrow-down',
    handler:function(){
        n=tree.getSelectionModel().getSelection()[0];
        //---only do something if its leaf=model
        if(n && n.isLeaf()){
            url= globals.module_url+'repository/dump/model/'+n.data.id;
            window.open(url);
        }
    }
});
var ModelRun = Ext.create('Ext.Action', {
    text:'Run',
    iconCls:'icon-play',
    handler:function(){
        n=tree.getSelectionModel().getSelection()[0];
        //---only do something if its leaf=model
        if(n && n.isLeaf()){
            url=globals.module_url+'engine/newcase/model/'+n.data.id;
            window.open(url);
        }
    }
});

var ModelRunManual = Ext.create('Ext.Action', {
    text:'Run Manual',
    iconCls:'icon-hand-right',
    handler:function(){
        n=tree.getSelectionModel().getSelection()[0];
        //---only do something if its leaf=model
        if(n && n.isLeaf()){
            url= globals.module_url+'engine/newcase/model/'+n.data.id+'/manual';
            window.open(url);
        }
    }
});
var ModelManager = Ext.create('Ext.Action', {
    text:'Manager',
    iconCls:'icon icon-bar-chart',
    handler:function(){
        n=tree.getSelectionModel().getSelection()[0];
        //---only do something if its leaf=model
        if(n && n.isLeaf()){
            url= globals.module_url+'case_manager/browse/model/'+n.data.id;
            window.open(url);
        }
    }
});

var ModelKPI = Ext.create('Ext.Action', {
    text:'KPI',
    iconCls:'icon-dashboard',
    handler:function(){
        n=tree.getSelectionModel().getSelection()[0];
        //---only do something if its leaf=model
        if(n && n.isLeaf()){
            url= globals.module_url+'kpi/editor/model/'+n.data.id;
            window.open(url);
        }
    }
});


var ToolBar=Ext.create('Ext.toolbar.Toolbar', {
    disabled: false,
    items: [
    ModelManager,
    ModelEdit,
    ModelRun,
    ModelKPI,
    ModelView,
    ModelDump,
    ModelRunManual,
    ModelImport,
    ModelExport,
    ModelCloudImport,
    ModelCloudExport
    ]
});

var modelPanel= Ext.create('Ext.Panel', {
    id:'modelPanel',
    autoScroll:true,
    listeners:{
//  render: load_model
}
});
        
var CenterPanel=Ext.create('Ext.Panel', {
    id: 'images-cont',
    title: 'Simple DataView (0 items selected)',
    autoScroll : true,
    items: Ext.create('Ext.view.View', {
        id:'images-view',
        store: Ext.data.StoreManager.lookup('ViewStore'),
        tpl: [
        '<tpl for=".">',
        '<div class="thumb-wrap" id="{id}">',
        '<div class="thumb"><img src="{module_url}assets/images/256x256/mimetypes/{imgurl}" title="{text}">',
        '<span class="x-editable">{text}<br/>{info}</span>',
        '</div>',
        '</div>',
        '</tpl>',
        '<div class="x-clear"></div>'
        ],
        multiSelect: true,
        trackOver: true,
        overItemCls: 'x-item-over',
        itemSelector: 'div.thumb-wrap',
        emptyText: 'No images to display',
        plugins: [
        Ext.create('Ext.ux.DataView.DragSelector', {}),
        Ext.create('Ext.ux.DataView.LabelEditor', {
            dataIndex: 'name'
        })
        ],
        prepareData: function(data) {
            Ext.apply(data, {
                shortName: Ext.util.Format.ellipsis(data.name, 15),
                sizeString: Ext.util.Format.fileSize(data.size),
                dateString: Ext.util.Format.date(data.lastmod, "m/d/Y g:i a")
            });
            return data;
        },
        listeners: {
            selectionchange: function(dv, nodes ){
                var l = nodes.length,
                s = l !== 1 ? 's' : '';
                this.up('panel').setTitle('Simple DataView (' + l + ' item' + s + ' selected)');
            }
        }
    })
});
//---Remove trail dc=.... from requests
Ext.Loader.setConfig({
    disableCaching : false
});
Ext.Ajax.setConfig({
    disableCaching : false
});
//--- this is 4 CodeIgniter smart urls
Ext.apply(Ext.data.AjaxProxy.prototype.actionMethods, {
    read: 'POST'
});
//---set ux paths
Ext.Loader.setConfig({
    enabled: true,
    paths: {
        'Ext.ux': globals.base_url+'jscript/ext/src/ux'
    }
});

Ext.require([
    'Ext.data.*',
    'Ext.ux.DataView.DragSelector',
    'Ext.ux.DataView.LabelEditor'
    ]);

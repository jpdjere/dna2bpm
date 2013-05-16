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
        'Ext.ux': globals.base_url+'jscript/ext/src/ux',
        'Ext.ux.grid': globals.module_url+'assets/jscript/ux/grid',
        'Ext.ux.grid.feature': globals.module_url+'assets/jscript/ux/grid/feature',
        'Ext.ux.container': globals.module_url+'assets/jscript/ux/container'
    }
});

Ext.require([
    'Ext.data.*',
    'Ext.grid.*',
    'Ext.ux.grid.feature.Tileview',
    'Ext.ux.container.SwitchButtonSegment',
    'Ext.grid.header.Container', // this fix a Ext JS dependency bug
    'Ext.view.TableChunker',
    'Ext.ux.grid.plugin.DragSelector'
    ]);

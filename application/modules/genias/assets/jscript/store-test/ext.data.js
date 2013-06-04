Ext.apply(Ext.data.AjaxProxy.prototype.actionMethods, {
    read: 'POST'
}); 
var provincias = new Ext.data.JsonStore({
    // store configs
    storeId: 'Provincias',

    proxy: {
        type: 'ajax',
        url: globals.module_url+'assets/json/provincias.json',
        reader: {
            type: 'json',
            root: 'data',
            idProperty: 'name'
        }
    },
    //alternatively, a Ext.data.Model name can be given (see Ext.data.Store for an example)
    fields: ['value', 'text']
});
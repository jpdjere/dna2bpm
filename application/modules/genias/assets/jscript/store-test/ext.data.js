/*
Ext.apply(Ext.data.AjaxProxy.prototype.actionMethods, {
    read: 'POST'
}); 
*/
var provincias = new Ext.data.JsonStore({
    // store configs
    storeId: 'Provincias',

    proxy: {
        type: 'ajax',
        url: globals.module_url+'assets/json/provincias.json',
        actionMethods:{
            read:'GET'
        },
        reader: {
            type: 'json',
            root: 'data',
            idProperty: 'name'
        }
    },
    listeners:{
        load:function(me,records, successful, eOpts ){
            for(j in records)
                document.getElementById('loading-msg').innerHTML +='value'+records[j].data['value']+' text:'+records[j].data['text']+'<br/>';
        }
    },
    //alternatively, a Ext.data.Model name can be given (see Ext.data.Store for an example)
    fields: ['value', 'text']
});
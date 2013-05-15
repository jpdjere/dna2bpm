/*
 *  This file defines all data stores used in the app
 *
 */


/*
 *                      GROUPS
 */
Ext.define('file', {
    extend: 'Ext.data.Model',
    fields: [
    'idfile',
    'name',
    'desc',
    'path',
    'user',
    'tags',
    {
        name: 'locked', 
        type: 'bool'
    }
    ]
});


/*
 *                      USERS
 */
Ext.define('User', {
    extend: 'Ext.data.Model',
    fields: [
    'idu',
    'nick',
    'name',
    'lastname',
    'email',
    'phone',
    'idnumber',
    'group',
    {
        name: 'locked', 
        type: 'bool'
    },
    {
        name: 'disabled', 
        type: 'bool'
    }
    ]
});

   
/*
 *      Tree Store
 */


Ext.create('Ext.data.TreeStore', {
    id:"TreeStore",
    autoLoad: false,
    proxy: {
        type: 'ajax',
        noCache: false,//---get rid of the ?dc=.... in urls
        url: globals.module_url+'fileman/get_tree/json'
    //url:'http://localhost/ext/examples/build/KitchenSink/ext-theme-neptune/resources/data/tree/check-nodes.json?_dc=1363724048632&sort=[{%22property%22%3A%22leaf%22%2C%22direction%22%3A%22ASC%22}%2C{%22property%22%3A%22text%22%2C%22direction%22%3A%22ASC%22}]&node=root'
    },
    sorters: [{
        property: 'leaf',
        direction: 'ASC'
    }, {
        property: 'text',
        direction: 'ASC'
    }],
    listeners:{}
        
});

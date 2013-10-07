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

function onTreeStoreLoad() {
    //tree.load_checked();
}
Ext.define('MenuItem', {
    extend: 'Ext.data.Model',
    fields: [
         'title',
        'target',
        'text',
        'cls' ,
        'iconCls',
        'priority',
        'info', 
    ]
}
);

Ext.create('Ext.data.TreeStore', {
    id: "TreeStore",
    autoLoad: false,
    allowSingle: false,
    proxy: {
        type: 'ajax',
        noCache: false, //---get rid of the ?dc=.... in urls
        api: {
            create: globals.module_url + 'admin/repository/create',
            read: globals.module_url + 'admin/repository/read',
            update: globals.module_url + 'admin/repository/update',
            destroy: globals.module_url + 'admin/repository/destroy'
        },
         writer: {
            type: 'json',
            allowSingle: false
        }
    },
    sorters: [{
            property: 'leaf',
            direction: 'ASC'
        }, {
            property: 'text',
            direction: 'ASC'
        }],
    listeners: {
        load: onTreeStoreLoad
    }

});


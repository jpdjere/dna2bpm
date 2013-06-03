/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
Ext.define('Writer.Person', {
    extend: 'Ext.data.Model',
    fields: [{
            name: 'id',
            type: 'int',
            useNull: true
        }

        , 'usuario_tablet'      //      Usuario 
                , '7586'                //      GenIA 
                , '7404'                //      Provincia 
                , 'fcc'                 //      FCC ID
                , 'mac'                 //      MAC address
                , '7408'                //      comentarios
                , '7411'                //      Empresa visitada
                , '7406'                //      idu
    ],
    /*VALIDACIONES*/

    validations: [/*{
     type: 'length',
     field: '7411',
     min: 1
     }*/]
});


/**
 * Function que valida REGEX MAC ADDRESS
 * 
 * @param mac
 * @cat validate
 * @type Vtype
 * @author Diego
 * 
 **/

Ext.form.VTypes["MACVal"] = /^(((\d|([a-f]|[A-F])){2}:){5}(\d|([a-f]|[A-F])){2})$|^(((\d|([a-f]|[A-F])){2}-){5}(\d|([a-f]|[A-F])){2})$|^([0-9a-f]{4}\.[0-9a-f]{4}\.[0-9a-f]{4})$/;
Ext.form.VTypes["MACText"] = "Ingrese una MAC address\n V&aacute;lida Ej: 5c-FF-35-7C-96-FB"
Ext.form.VTypes["MAC"] = function(v) {
    return Ext.form.VTypes["MACVal"].test(v);
}


//----Define the data model 4 forms
Ext.define('OpcionModel', {
    extend: "Ext.data.Model",
    fields: ['value', 'text']

});

Ext.define('OpcionModelPtdo', {
    extend: "Ext.data.Model",
    fields: ['idrel', 'value', 'text']

});

Ext.define('OpcionUser', {
    extend: "Ext.data.Model",
    fields: ['idu', 'nick']
});


/*
 * @Name Users
 * @type Store 
 * 
 */
var userStore = Ext.create('Ext.data.Store', {
    id: 'userStore',
    autoLoad: true,
    model: 'OpcionUser',
    proxy: {
        type: 'ajax',
        url: globals.base_url + 'genias/users/get_user/read', // url that will load data with respect to start and limit params        
        noCache: false,
        reader: {
            type: 'json',
            root: 'rows',
            totalProperty: 'totalCount'
        }

    },
    listeners: {
        load: function() {
            store.load();
        }
    }


});


/*
 * @Name Genias
 * @type Store 
 * 
 */
var GeniaStore = Ext.create('Ext.data.Store', {
    id: 'GeniaStore',
    autoLoad: true,
    model: 'OpcionModel',
    proxy: {
        type: 'ajax',
        url: globals.base_url + '/form/get_option/711', // url that will load data with respect to start and limit params        
        noCache: false,
        reader: {
            type: 'json',
            root: 'rows',
            totalProperty: 'totalCount'
        }
    }
});
/*
 * @Name Provincias
 * @type Store
 * 
 */
var ProvinciaStore = Ext.create('Ext.data.Store', {
    id: 'ProvinciaStore',
    autoLoad: true,
    model: 'OpcionModel',
    proxy: {
        type: 'ajax',
        url: globals.base_url + '/form/get_option/39', // url that will load data with respect to start and limit params        
        noCache: false,
        useLocalStorage: true,
        reader: {
            type: 'json',
            root: 'rows',
            totalProperty: 'totalCount'
        }
    }
});



/*ON LINE APP */
if (navigator.onLine) {
    var store = Ext.create('Ext.data.Store', {
        model: 'Writer.Person',
        autoLoad: false,
        autoSync: true,
        proxy: {
            type: 'ajax',
            api: {
                read: 'process/ViewTablet', //'genias/app/geniasdev/view',
                create: 'process/InsertTablet',
                update: 'process/InsertTablet',
                destroy: '', //'genias/app/geniasdev/destroy'
            },
            reader: {
                type: 'json',
                successProperty: 'success',
                root: 'data',
                messageProperty: 'message'
            },
            writer: {
                type: 'json',
                writeAllFields: true
            },
            listeners: {
                exception: function(proxy, response, operation) {
                    Ext.MessageBox.show({
                        title: 'ERROR',
                        msg: operation.getError(),
                        icon: Ext.MessageBox.ERROR,
                        buttons: Ext.Msg.OK
                    });
                }
            }
        },
        listeners: {
            write: function(proxy, operation) {
                if (operation.action == 'destroy') {
                    main.child('#form').setActiveRecord(null);
                }
                //Ext.example.msg(operation.action, operation.resultSet.message);
            }
        }
    });
} 
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
                , 'usuario_tablet'    //      Usuario 
                , '7586'    //      GenIA 
                , '7404'    //      Provincia 
                , 'fcc'     //      FCC ID
                , 'mac'     //      MAC address
                , 'qr'      //      QR CODE
                , '7408'   //      comentarios
    ],
    /*VALIDACIONES*/

    validations: [{
            type: 'length',
            field: '7411',
            min: 1
        }]
});


//----Define the data model 4 forms
Ext.define('OpcionModel', {
    extend: "Ext.data.Model",
    fields: ['value', 'text']

});


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

var store = Ext.create('Ext.data.Store', {
    model: 'Writer.Person',
    autoLoad: true,
    autoSync: true,
    proxy: {
        type: 'ajax',
        api: {
            read: 'tablets/process/View', //'genias/app/geniasdev/view',
            create: 'process/Insert',
            update: 'process/Insert',
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

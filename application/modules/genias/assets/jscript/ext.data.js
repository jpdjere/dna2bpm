/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
Ext.define('formModel', {
    extend: 'Ext.data.Model',
    fields: [{
        name: 'id',
        type: 'int',
        useNull: true
    }
    , '7586' // 	GenIA 
    , '7406' // 	Usuario 
    , '7404' // 	Provincia 
    , '7405' // 	Partido 
    , '7411' // 	Empresa visitada 
    , '7407' // 	Fecha de la Visita 
    , '7408' // 	Comentarios 
    , '7409' // 	Origen 
    , '7818' // 	Task
    , '7819' // 	Longitud
    , '7820' // 	Latitud
        
    ],
    /*VALIDACIONES*/

    validations: [{
        type: 'length',
        field: '7411',
        min: 1
    }]
});

Ext.define('EmpresaModel', {
    extend: 'Ext.data.Model',
    fields: [{
        name: 'id',
        type: 'int',
        useNull: true
    }
    ,'1693'  // Nombre de la empresa
    ,'1695'  // CUIT
    , '7819' // 	Longitud
    , '7820' // 	Latitud
    , '4651' // 	Partido
    , '4653' //         Direccion
    , '1699' // 	Localidad
    ]
});


/**
 * Function que valida el algoritmo del Nro de CUIT
 * 
 * @param cuit
 * @cat validate
 * @type Vtype
 * @author Diego
 * 
 **/
Ext.apply(Ext.form.field.VTypes, {
    CUIT: function(cuit) {
        if (typeof (cuit) == 'undefined')
            return true;
        cuit = cuit.toString().replace(/[-_]/g, "");
        if (cuit == '')
            return true; //No estamos validando si el campo esta vacio, eso queda para el "required"
        if (cuit.length != 11)
            return false;
        else {
            var mult = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
            var total = 0;
            for (var i = 0; i < mult.length; i++) {
                total += parseInt(cuit[i]) * mult[i];
            }
            var mod = total % 11;
            var digito = mod == 0 ? 0 : mod == 1 ? 9 : 11 - mod;
        }
        return digito == parseInt(cuit[10]);
    },
    CUITText: 'Ingrese un Nro de C.U.I.T. V&aacute;lido',
    CUITMask: /[\d\.]/i
});



//----Define the data model 4 forms
Ext.define('OpcionModel', {
    extend: "Ext.data.Model",
    fields: ['value', 'text']

});

Ext.define('OpcionModelPtdo', {
    extend: "Ext.data.Model",
    fields: ['idrel', 'value', 'text']

});




/*
 * @Name Empresas
  * @type Store 
 * 
 */
var EmpresaStore = Ext.create('Ext.data.Store', {
    id: 'EmpresaStore',
    autoLoad: true,
    model: 'EmpresaModel',
    proxy: {
        type: 'ajax',
        url: globals.module_url + 'empresas',
        actionMethods: {
            read: 'GET'
        },
        noCache: false,
        useLocalStorage: true,
        reader: {
            type: 'json',
            root: 'rows',
            totalProperty: 'totalCount'
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
        url: globals.module_url + 'assets/json/genias.json',
        actionMethods: {
            read: 'GET'
        },
        noCache: false,
        useLocalStorage: true,
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
        //url: globals.base_url + '/form/get_option/39', // url that will load data with respect to start and limit params 
        url: globals.module_url + 'assets/json/provincias.json',
        actionMethods: {
            read: 'GET'
        },
        noCache: false,
        useLocalStorage: true,
        reader: {
            type: 'json',
            root: 'rows',
            totalProperty: 'totalCount'
        }
    }
});

/*
 * @Name Partidos
 * @type Store 
 * 
 */
var PartidoStore = Ext.create('Ext.data.Store', {
    id: 'PartidoStore',
    autoLoad: true,
    model: 'OpcionModelPtdo',
    proxy: {
        type: 'ajax',        
        url: globals.module_url + 'assets/json/partidos.json',
        actionMethods: {
            read: 'GET'
        },
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
        model: 'formModel',
        autoLoad: true,
        autoSync: true,
        proxy: {
            type: 'ajax',
            api: {
                read: globals.module_url + 'process/View', //'genias/app/geniasdev/view',
                create: globals.module_url + 'process/Insert',
                update: globals.module_url + 'process/Insert',
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
} else {
    /*OFFLINE APP*/
    var store = Ext.create('Ext.data.Store', {
        model: 'formModel',
        autoLoad: true,
        autoSync: true,
        proxy: {
            type: 'localstorage',
            id: 'genias'
        },
        listeners: {
            write: function(proxy, operation) {
                if (operation.action == 'destroy') {
                    main.child('#form').setActiveRecord(null);
                }
                store.load();                
                store.sync();
            //Ext.example.msg(operation.action, operation.resultSet.message);
            }
        }
    });
/*end OFFLINE APP*/
}


var storeOffline = Ext.create('Ext.data.Store', {
    model: 'formModel',
    autoLoad: true,
    autoSync: true,
    proxy: {
        type: 'localstorage',
        id: 'genias'
    },
    listeners: {
        load: function() {
            this.each(function(record) {
                //console.log("check" + JSON.stringify(record.data, null, 4)); 
                //store.add(record.data);
                Ext.Ajax.request({
                    url: globals.module_url + 'process/Insert',
                    method: 'POST',
                    root: 'data',
                    type: 'json',
                    params: JSON.stringify(record.data, null, 4),
                    callback: function(options, success, response) {

                        var didPost = true,
                        o;

                        if (success) {
                            try {
                                o = Ext.decode(response.responseText);
                                didPost = o.success === true;
                            } catch (e) {
                                didPost = false;
                            }
                        } else {
                            didPost = false;
                        }
                    }
                });
            });
            // Sync the online store
            store.sync();
        // Remove data from offline store
        //storeOffline.removeAll();
        },
        write: function(proxy, operation) {
            if (operation.action == 'destroy') {
                store.child('#form').setActiveRecord(null);
            }
            Ext.example.msg(operation.action, operation.resultSet.message);
        }
    }
});
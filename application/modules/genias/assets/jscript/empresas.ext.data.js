/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



Ext.define('visitaModel', {
    extend: 'Ext.data.Model',
    fields: [    'fecha' // 	Fecha de la Visita 
                , 'cuit'
                , 'nota' // 	Comentarios 
                
    ]
});

Ext.define('EmpresaModel', {
    extend: 'Ext.data.Model',
    fields: [{
            name: 'id',
            type: 'int',
            useNull: true
        }
                , '1693'  //     Nombre de la empresa
                , '1695'  //     CUIT
                , '7819' // 	Longitud
                , '7820' // 	Latitud
                , '4651' // 	Provincia
                , '4653' //     Calle Ruta
                , '4654' //     Nro /km
                , '4655' //     Piso
                , '4656' //     Dto Oficina
                , '1699' // 	Partido
                , 'status' //      Syncro data (date?) / dirty
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
    ,
    sorters: [{
            property: '1693',
            direction: 'ASC'
        }]
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


var storeEmpresaOffline = Ext.create('Ext.data.Store', {
        model: 'EmpresaModel',
        autoLoad: true,
        autoSync: true,
        proxy: {
            type: 'localstorage',
            id: 'empresas'
        },
        listeners: {
            write: function(proxy, operation) {
                if (operation.action == 'destroy') {
                    main.child('#form').setActiveRecord(null);
                }
               // storeEmpresaOffline.load();
               // storeEmpresaOffline.sync();
                //Ext.example.msg(operation.action, operation.resultSet.message);
            }
        }
    });
    
    
    var storeVisitaOffline = Ext.create('Ext.data.Store', {
        model: 'visitaModel',
        autoLoad: true,
        autoSync: true,
        proxy: {
            type: 'localstorage',
            id: 'visitas'
        },
        listeners: {
            write: function(proxy, operation) {
                if (operation.action == 'destroy') {
                    main.child('#form').setActiveRecord(null);
                }
               // storeEmpresaOffline.load();
               // storeEmpresaOffline.sync();
                //Ext.example.msg(operation.action, operation.resultSet.message);
            }
        }
    });
    

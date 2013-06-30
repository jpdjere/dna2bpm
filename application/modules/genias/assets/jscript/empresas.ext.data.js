/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



Ext.define('visitaModel', {
    extend: 'Ext.data.Model',
    fields: [
    'fecha' // 	Fecha de la Visita 
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
    ,'notas'   // solo para el form de notas
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
    //CUITMask: /[\d\.]/i
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
    ,
    listeners:{
        load:function(){
            storeEmpresaOffline.load(function(){
                //actualizo los modificados
                storeEmpresaOffline.each(function(rec) {
                    item=EmpresaStore.find('1695',rec.get('1695'));
                    //console.log(item,rec.data);
                    if(item>=0){
                        //----Actualizo los datos con lo modificado
                        EmpresaStore.getAt(item).set(rec.data);
                        
                    } else {
                        //-----agrego al store
                        EmpresaStore.add(rec);
                    }
                });
            });
            
        }
    }
});
/*
 * @Name VisitasStore
 * @type Store 
 * 
 */
var VisitasStore = Ext.create('Ext.data.Store', {
    id: 'VisitasStore',
    autoLoad: true,
    model: 'visitaModel',
    proxy: {
        type: 'ajax',
        url: globals.module_url + 'visitas',
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
        property: 'fecha',
        direction: 'DESC'
    }]
    ,
    cuitFilter: function (cuit) {
        Ext.data.Store.prototype.clearFilter.call(this);
        Ext.data.Store.prototype.filter.call(this, 'cuit', cuit);
    },
    listeners:{
        load:function(){
        
            VisitasStore.cuitFilter('-1');
            storeVisitaOffline.load(function(){
                //actualizo los modificados
                storeVisitaOffline.each(function(rec) {
                    VisitasStore.add(rec);
                    VisitasStore.cuitFilter('-1');
                });
            });
            
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


var storeEmpresaOffline = Ext.create('Ext.data.Store', {
    model: 'EmpresaModel',
    autoLoad: false,
    autoSync: true,
    proxy: {
        type: 'localstorage',
        id: 'empresas'
    }
});


var storeVisitaOffline = Ext.create('Ext.data.Store', {
    model: 'visitaModel',
    autoLoad: true,
    autoSync: true,
    proxy: {
        type: 'localstorage',
        id: 'visitas'
    }
});




var storeEmpresa = Ext.create('Ext.data.Store', {
    model: 'EmpresaModel',
    autoLoad: false,
    autoSync: true,
    proxy: {
        type: 'ajax',
        id: 'store',
        api: {
            read: globals.module_url + 'process/View', //'genias/app/geniasdev/view',
            create: globals.module_url + 'process/Insert',
            update: globals.module_url + 'process/Insert',
            destroy: '' //'genias/app/geniasdev/destroy'
        },
        reader: {
            type: 'json',
            successProperty: 'success',
            root: 'data',
            messageProperty: 'message'
        },
        writer: {
            type: 'json',
            writeAllFields: true,
            allowSingle:false
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
        }
    }
});

var storeVisita = Ext.create('Ext.data.Store', {
    model: 'EmpresaModel',
    autoLoad: false,
    autoSync: true,
    proxy: {
        type: 'ajax',
        id: 'store',
        api: {
            read: globals.module_url + 'process/View', //'genias/app/geniasdev/view',
            create: globals.module_url + 'process/Insert',
            update: globals.module_url + 'process/Insert',
            destroy: '' //'genias/app/geniasdev/destroy'
        },
        reader: {
            type: 'json',
            successProperty: 'success',
            root: 'data',
            messageProperty: 'message'
        },
        writer: {
            type: 'json',
            writeAllFields: true,
            allowSingle:false
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
        }
    }
});

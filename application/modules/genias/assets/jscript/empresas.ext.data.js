//----Define the data model 4 forms
Ext.define('OpcionModel', {
    extend: "Ext.data.Model",
    fields: ['value', 'text']

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


/*
 * @Name Empresas
 * @type Store 
 * 
 */

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
                , 'partido_txt' // 	Partido en texto ->viene del server
                , 'status' //      Syncro data (date?) / dirty
                , 'notas'   // solo para el form de notas
                , 'task'    //Taras asociadas desde la agenda

                , '1694'    // Tipo de empresa
                , '1698'    //cod postal
                , '1701'    //telefonos
                , '1703'    //email
                , '1704'    //web
                , '1711'    //Cantidad de Empleados actual  
                /*contacto*/
                , '7876'    // Apellido y Nombre del Contacto
                , '7877'    // E-mail del Contacto
                , '7878'    // Rubro de la Empresa                
                /*PLANTA*/
                , '7879'    // Superficie Cubierta
                , '7880'    // Posesión (idopcion = 729)
                , '1715'    // Productos o servicios que Ofrece
                /* PRODUCCION*/
                , '7881'    // Tiene componentes importados (idopcion = 15)
                , '7882'    // Pueden ser reemplazados? (idopcion = 15)
                , '7883'    // Tiene capacidad para exportar? (idopcion = 15)
                , '1716'    // Mercado destino (idopcion = 88)
                , '7884'    // Proveedores
                , 'C7663'    // La empresa ha realizado o realiza acciones vinculadas a la Responsabilidad Social (idopcion = 716)
                , '7665'    // Registro Único de Organizaciones de Responsabilidad Social (idopcion = 715)
    ]
});

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
    listeners: {
        load: function() {
            storeEmpresaOffline.load(function() {
                //actualizo los modificados
                storeEmpresaOffline.each(function(rec) {
                    item = EmpresaStore.find('1695', rec.get('1695'));
                    //console.log(item,rec.data);
                    if (item >= 0) {
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

var storeEmpresaOffline = Ext.create('Ext.data.Store', {
    model: 'EmpresaModel',
    autoLoad: false,
    autoSync: true,
    proxy: {
        type: 'localstorage',
        id: 'empresas'
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
            read: globals.module_url + 'empresas_remote/View',
            create: globals.module_url + 'empresas_remote/Insert',
            update: globals.module_url + 'empresas_remote/Insert',
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
            allowSingle: false
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



/*
 * @Name VisitasStore
 * @type Store 
 * 
 */
Ext.define('visitaModel', {
    extend: 'Ext.data.Model',
    fields: [
        'fecha' // 	Fecha de la Visita 
                , 'cuit'
                , 'nota' // 	Comentarios 
                , 'tipovisita' //tipo de visita
                , 'otros' // para tipo de visita otros

    ]
});

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
    cuitFilter: function(cuit) {
        Ext.data.Store.prototype.clearFilter.call(this);
        Ext.data.Store.prototype.filter.call(this, 'cuit', cuit);
    },
    listeners: {
        load: function() {
            VisitasStore.cuitFilter('-1');
            storeVisitaOffline.load(function() {
                //actualizo los modificados
                storeVisitaOffline.each(function(rec) {
                    VisitasStore.add(rec);
                    VisitasStore.cuitFilter('-1');
                });
            });

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
            read: globals.module_url + 'vistas_remote/View',
            create: globals.module_url + 'visitas_remote/Insert',
            update: globals.module_url + 'visitas_remote/Insert',
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
            allowSingle: false
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

var storeVisitaOffline = Ext.create('Ext.data.Store', {
    model: 'visitaModel',
    autoLoad: true,
    autoSync: true,
    proxy: {
        type: 'localstorage',
        id: 'visitas'
    }
});



/*
 * @Name EncuestasStore
 * @type Store 
 * 
 */

Ext.define('encuestasModel', {
    extend: 'Ext.data.Model',
    fields: [
        'fecha' // 	Fecha de la Visita 
                , 'cuit'
                , '7663'        // 	Ha realizado/a acciones vinculadas a la Responsabilidad Social 
                , '7664'        //      Tienen relaci&oacute;n con organismos gubernamentales
                , '7883'       //      Registro Unico de Organizaciones de Responsabilidad Social
                , '7886'       //      Modos de Financiamiento
                , '7887'       //      Con Programas Sepyme/Ministerio de Industria

    ]
});

var EncuestasStore = Ext.create('Ext.data.Store', {
    id: 'EncuestasStore',
    autoLoad: true,
    model: 'visitaModel',
    proxy: {
        type: 'ajax',
        url: globals.module_url + 'encuestas',
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
    cuitFilter: function(cuit) {
        Ext.data.Store.prototype.clearFilter.call(this);
        Ext.data.Store.prototype.filter.call(this, 'cuit', cuit);
    },
    listeners: {
        load: function() {
            EncuestasStore.cuitFilter('-1');
            storeEncuestasOffline.load(function() {
                //actualizo los modificados
                storeEncuestasOffline.each(function(rec) {
                    EncuestasStore.add(rec);
                    EncuestasStore.cuitFilter('-1');
                });
            });

        }
    }
});

var storeEncuesta = Ext.create('Ext.data.Store', {
    model: 'EncuestaModel',
    autoLoad: false,
    autoSync: true,
    proxy: {
        type: 'ajax',
        id: 'store',
        api: {
            read: globals.module_url + 'encuesta_remote/View',
            create: globals.module_url + 'encuesta_remote/Insert',
            update: globals.module_url + 'encuesta_remote/Insert',
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
            allowSingle: false
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

var storeEncuestasOffline = Ext.create('Ext.data.Store', {
    model: 'EncuestasModel',
    autoLoad: true,
    autoSync: true,
    proxy: {
        type: 'localstorage',
        id: 'encuestas'
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

Ext.define('OpcionModelPtdo', {
    extend: "Ext.data.Model",
    fields: ['idrel', 'value', 'text']

});



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
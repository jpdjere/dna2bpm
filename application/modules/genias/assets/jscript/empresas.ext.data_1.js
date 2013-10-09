var checkids = function() {

    me = Ext.StoreMgr.lookup('EmpresaId');
    var count = me.count();
    pb.updateText('Cargando ' + count + ' Empresas...');
    i = 0;
    u = 0;
    insert = false;
    me.each(function(rec) {
        i++
        pb.updateProgress(i / count);
        if (rec.data['id']) {
            index = storeEmpresaOffline.find('id', rec.data['id']);
            if (index >= 0) {
                item = storeEmpresaOffline.getAt(index);
                if (item.data['checksum'] !== rec.data['checksum']) {
                    insert = true;
                    u++;
                    //----borro el registro actual
                }
                //----Actualizo los datos con lo modificado


            } else {
                //-----agrego al store
                insert = true;
            }
            //console.log(index,item.data['checksum'] !== rec.data['checksum'], storeEmpresaOffline.count());

            if (insert) {
            }

        }//---end if id
        /*
         * 
         if (i == 10)
         return false;
         */
    });
    pb.updateText('Checkeando ' + i + ' de ' + count + ' Empresas ' + u + ' Actualizadas.');
    //---update store
}
var loadids = function() {
    Ext.apply(Ext.data.Connection.prototype, {
        async: false
    });
    me = Ext.StoreMgr.lookup('EmpresaId');
    var count = me.count();
    pb.updateText('Cargando ' + count + ' Empresas...');
    i = 0;
    u = 0;
    insert = false;
    me.each(function(rec) {
        i++
        pb.updateProgress(i / count);
        pb.updateText('Checkeando ' + i + ' de ' + count + ' Empresas ' + u + ' Actualizadas.');
        url = globals.module_url + 'loader/empresa/' + rec.data['id'];
        insert = false;
        if (rec.data['id']) {
            index = storeEmpresaOffline.find('id', rec.data['id']);
            if (index >= 0) {
                item = storeEmpresaOffline.getAt(index);
                if (item.data['checksum'] !== rec.data['checksum']) {
                    insert = true;
                    u++;
                    //----borro el registro actual
                }
                //----Actualizo los datos con lo modificado


            } else {
                //-----agrego al store
                insert = true;
            }
            //console.log(index,item.data['checksum'] !== rec.data['checksum'], storeEmpresaOffline.count());

            if (insert) {
                Ext.Ajax.request({
                    // the url to the remote source
                    url: url,
                    method: 'POST',
                    // define a handler for request success
                    success: function(response, options) {
                        data = Ext.JSON.decode(response.responseText);
                        data['checksum'] = rec.data['checksum'];
                        newRec = new EmpresaModel(data);
                        newRec.setDirty();
                        storeEmpresaOffline.add(newRec);
                        storeEmpresaOffline.sync();


                    },
                    // NO errors ! ;)
                    failure: function(response, options) {
                        alert('Error Loading:' + response.err);
                    }
                });
            }

        }//---end if id

        if (i == 2500)
            return false;

    });
    //---update store
    storeEmpresaOffline.sync();
    return true;
}
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
                /*CONTACTO*/
                , '7876'    // Apellido y Nombre del Contacto
                , '7877'    // E-mail del Contacto
                , '7878'    // Rubro de la Empresa                
                /*PLANTA*/
                , '7879'    // Superficie Cubierta
                , '7880'    // Posesi�n (idopcion = 729)
                , '1715'    // Productos o servicios que Ofrece
                /* PRODUCCION*/
                , '7881'    // Tiene componentes importados (idopcion = 15)
                , '7882'    // Pueden ser reemplazados? (idopcion = 15)
                , '7883'    // Tiene capacidad para exportar? (idopcion = 15)
                , '1716'    // Mercado destino (idopcion = 88)
                , '7884'    // Proveedores
                , 'C7663'    // La empresa ha realizado o realiza acciones vinculadas a la Responsabilidad Social (idopcion = 716)
                , '7665'    // Registro �nico de Organizaciones de Responsabilidad Social (idopcion = 715)
                , 'origen'  //Origen de los datos Genia 2013
                , 'origenGenia' //Origen de los datos Genia 2013 Genia
                , 'checksum' //---hash para saber si hay que actualizar
    ]
});
var storeEmpresaOffline = Ext.create('Ext.data.Store', {
    model: 'EmpresaModel',
    autoLoad: false,
    autoSync: true,
    proxy: {
        id: 'empresas',
        type: 'localstorage'
    }
});

var storeEmpresaId = Ext.create('Ext.data.Store', {
    id: 'EmpresaId',
    autoLoad: true,
    model: 'EmpresaModel',
    proxy: {
        type: 'ajax',
        url: globals.module_url + 'loader/empresas_id',
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

            , listeners: {
        load: function() {
            storeEmpresaOffline.load(loadids);
        }
    }

});



var loadids = function() {
    Ext.apply(Ext.data.Connection.prototype, {
        async: false
    });
    me = Ext.StoreMgr.lookup('InstitucionId');
    var count = me.count();
    pb.updateText('Cargando ' + count + ' Institucions...');
    i = 0;
    u = 0;
    insert = false;
    me.each(function(rec) {
        i++
        pb.updateProgress(i / count);
        pb.updateText('Checkeando ' + i + ' de ' + count + ' Institucions ' + u + ' Actualizadas.');
        url = globals.module_url + 'loader/empresa/' + rec.data['id'];
        insert = false;
        if (rec.data['id']) {
            index = storeInstitucionOffline.find('id', rec.data['id']);
            if (index >= 0) {
                item = storeInstitucionOffline.getAt(index);
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
            //console.log(index,item.data['checksum'] !== rec.data['checksum'], storeInstitucionOffline.count());

            if (insert) {
                Ext.Ajax.request({
                    // the url to the remote source
                    url: url,
                    method: 'POST',
                    // define a handler for request success
                    success: function(response, options) {
                        data = Ext.JSON.decode(response.responseText);
                        data['checksum'] = rec.data['checksum'];
                        newRec = new InstitucionModel(data);
                        newRec.setDirty();
                        storeInstitucionOffline.add(newRec);
                        storeInstitucionOffline.sync();
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
    storeInstitucionOffline.sync();
    return true;
}

/*
 * @Name Institucions
 * @type Store 
 * 
 */

Ext.define('InstitucionModel', {
    extend: 'Ext.data.Model',
    fields: [{
            name: 'id',
            type: 'int',
            useNull: true
        },
        '4896', //Nombre
        '4897', //Provincia (39)
        '8102', // Partido (58)
        '8103', // Localidad
        '8104', // Tipo (495)
        '8108', // Teléfono
        '6196', // E-mail
        '8111', //  Pagina web 
        '8109', // Latitud Institucion
        '8110', // Longitud Institucion
        //-----------Contacto
        '8105', // Nombre del Contacto
        '8107', // Cargo del Contacto
        '8117', // telefonos Contacto
        '8116', // email Contacto
        //------------DOmicilio
        '8106', // nro / Km
        '8112', //  Calle / Ruta 
        '8113', //  Piso
        '8114', //  Dto / Oficina 
        '8115', //  CP
        'origen', //Origen de los datos Genia 2013
        'origenGenia', //Origen de los datos Genia 2013 Genia
        'checksum' //---hash para saber si hay que actualizar
    ]
});
var InstitucionStore = Ext.create('Ext.data.Store', {
    id: 'InstitucionStore',
    autoLoad: true,
    model: 'InstitucionModel',
     proxy: {
        type: 'ajax',
        api: {
            read: globals.module_url + 'instituciones',
            create: globals.module_url + 'instituciones_remote/Insert',
            update: globals.module_url + 'instituciones_remote/Insert',
            destroy: '' //'genias/app/geniasdev/destroy'
        },
        reader: {
            type: 'json',
            root: 'rows',
            totalProperty: 'totalCount'
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
    }
    ,
    sorters: [{
            property: '4896',
            direction: 'ASC'
        }]
});
var storeInstitucionOffline = Ext.create('Ext.data.Store', {
    model: 'InstitucionModel',
    autoLoad: false,
    autoSync: true,
    proxy: {
        id: 'instituiones',
        type: 'localstorage'
    }
});

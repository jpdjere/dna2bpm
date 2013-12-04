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
        '8105', // Nombre del Contacto
        '8106', //Apellido del Contacto
        '8107', // Cargo del Contacto
        '8108', // Teléfono
        '6196', // E-mail
        '8109', // Latitud Institucion
        '8110', // Longitud Institucion
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
        url: globals.module_url + 'instituciones',
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
            property: '4896',
            direction: 'ASC'
        }]
});
var storeInstitucionOffline = Ext.create('Ext.data.Store', {
    model: 'InstitucionModel',
    autoLoad: false,
    autoSync: true,
    proxy: {
        id: 'empresas',
        type: 'localstorage'
    }
});

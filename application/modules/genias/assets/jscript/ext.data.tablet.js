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
                , '7411' // 	Empresa visitada 
    ],
    /*VALIDACIONES*/

    validations: [/*{
            type: 'length',
            field: '7411',
            min: 1
        }*/]
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
        autoLoad: true,
        autoSync: true,
        proxy: {
            type: 'ajax',
            api: {
                read: 'process/ViewTablet', //'genias/app/geniasdev/view',
                create: 'process/InsertTablet',
                update: 'process/Insert2',
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
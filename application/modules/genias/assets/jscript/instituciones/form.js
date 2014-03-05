Ext.define('InstitucionCombo', {
    extend: 'Ext.form.Panel',
    alias: 'widget.institucionCombo',
    items: [
        {
            xtype: 'combo',
            name: 'website_combo',
            id: 'website_comboid',
            store: InstitucionStore,
            displayField: '1693',
            queryMode: 'local'
        },
        {
            xtype: 'textfield',
            name: '1693',
            id: 'urlfield_id'
        },
        {
            xtype: 'button',
            text: 'Aagregar',
            listeners: {
                click: function() {
                    // get the selected item.
                    var urltextfield = Ext.getCmp('urlfield_id');
                    var v = urltextfield.getValue();

                    if (v != null && v != "")
                    {
                        var websitecombo = Ext.getCmp('website_comboid');
                        var p = Ext.create('Website', {url: v});

                        websitecombo.store.add(p);

                        //clear the field
                        urltextfield.setValue('');
                    }
                }
            }
        }
    ]
});



var countSync = function() {
    var getCount = storeEncuestasOffline.getCount() + storeVisitaOfflineInst.getCount() + storeInstitucionOffline.getCount() + storeVisitaOfflineDeleteInst.getCount();
    Ext.getCmp('btnSync').setText('Hay (' + getCount + ') para actualizar');
}
//==== BOTON POSICIONAR AUT ====// 

var btnMap = Ext.create('Ext.Action', {
    fieldLabel: '',
    text: '<i class="icon icon-map-marker"></i> Posicionar',
    listeners: {
        click: function() {
            if (navigator && navigator.geolocation) {
                var nav = navigator.geolocation.getCurrentPosition(function(position) {

                    var logitud = position.coords.longitude;
                    Ext.getCmp('long').setValue(logitud);

                    var latitud = position.coords.latitude;
                    Ext.getCmp('lat').setValue(latitud);

                    Ext.getCmp('longLayDisplay').setValue("Longitud: " + logitud + ' Latitud: ' + latitud);

                    //return logitud;
                }, function(error) {
                    return '0';
                });
            }

        }
    }

});

//==== BOTON POSICIONAR MANUAL ====// 

var markers = [];
//var myLong = Ext.getCmp('mylong').getValue();
var mymap = Ext.create('Ext.Panel', {
    layout: 'fit',
    id: 'mymap',
    height: 300,
    hideLabel: true,
    hidden: false,
    hideMode: 'display',
    items: [
        {
            xtype: 'gmappanel',
            gmapType: 'map',
            id: 'mymap2',
            zoomLevel: 8,
            setCenter: {
                lat: 0,
                lng: 0
            },
            listeners: {
                click: function(latLong, map) {
                    for (var i = 0; i < markers.length; i++) {
                        markers[i].setMap(null);
                    }

                    marker = new google.maps.Marker({
                        position: latLong,
                        map: map
                    });
                    markers.push(marker);

                    // Guardo Lat Long
                    Ext.getCmp('long').setValue(latLong.lng());
                    Ext.getCmp('lat').setValue(latLong.lat());
                    Ext.getCmp('longLayDisplay').setValue("Longitud: " + latLong.lng() + ' Latitud: ' + latLong.lat());
                },
                mapready: function(obj, map) {
                    geoFindMe(map);
                    mymap.hide();
                    // 
                }
            }
        }
    ]
});


// Muestra el mapa
var btnMapManual = Ext.create('Ext.Action', {
    fieldLabel: '',
    text: '<i class="icon icon-map-marker"></i> Posicionar Manual',
    listeners: {
        click: function() {
            if (mymap.hidden) {
                mymap.show();
            } else {
                mymap.hide();
            }
        }
    }
});


//==== BOTON NEW ====//

var btnNew = Ext.create('Ext.Action', {
    id: 'btn_new',
    xtype: 'button',
    text: '<i class="icon icon-plus"></i> Agregar',
    handler: function() {
        InstitucionForm.loadRecord(Ext.create('InstitucionModel', {}));
        /*Reseteo si hubiera una tarea asociada anterio*/
        InstitucionForm.params['task'] = null;
        Ext.getCmp('task').setValue("");
    }
});

//==== BOTON SAVE ====////
var btnSave = Ext.create('Ext.Action', {
    id: 'btn_save',
    disabled: true,
    xtype: 'button',
    text: '<i class="icon icon-save"></i> Guardar datos Institucion',
    handler: function() {
        var form = InstitucionForm;

        if (!form.isValid()) {
            Ext.Msg.alert('Encenario Pyme', '<h5>Complete los campos correctamente</h5>');
        } else {
            var record = form.getRecord();
            if (record) {
                //----es uno del grid
                form.getForm().updateRecord(record);
            }
            /*CHECKBOX*/
            var values = form.getValues();
            record.set(values);
            record.set('1716', values["1716"]);
            //---busco por cuit
            if (InstitucionStore.find('4896', record.get('4896')) == -1) {
                //---si no estaba lo agrego al online
                InstitucionStore.add(record);
            }
            storeInstitucionOffline.add(record);
            storeInstitucionOffline.sync();


            /*Sync Button*/
            countSync();
        }
    }

});

//==== BOTON SAVE VISITA ====//

var btnSaveVisita = Ext.create('Ext.Action', {
    id: 'btn_save_visita',
    disabled: true,
    xtype: 'button',
    text: '<i class="icon icon-save"></i> Guardar Visita Institucion',
    handler: function() {
        var form = VisitaForm;
        var formInstitucion = InstitucionForm;
        if (!form.isValid()) {
            Ext.Msg.alert('Encenario Pyme', '<h5>Complete los campos correctamente</h5>');
        } else {
            var recordInstitucion = formInstitucion.getRecord();
            var record = form.getRecord();
            if (record) {
                //----es uno del grid
                form.getForm().updateRecord(record);
            }
            data = form.getValues();
            dataInstitucion = formInstitucion.getValues();
            //var d = data['fecha']; //new Date();            
            //var n = d.toISOString();                     
            if (dataInstitucion['4896']) {
                visitaRecord = Ext.create('visitaModelInst', {
                    fecha: data['fecha'], //n,
                    cuit: dataInstitucion['4896'],
                    nota: data['nota'],
                    tipo: data['tipovisita'],
                    otros: data['otros'],
                    7898: data['7898']
                });
                //--agrego al que se usa para visualizar    

                VisitasStoreInst.add(visitaRecord);
                //---busco por cuit            
                //--agrego al que se usa para syncro y persistencia
                storeVisitaOfflineInst.add(visitaRecord);
                storeVisitaOfflineInst.sync();
                VisitasGrid.refresh();
                /*Actualizo listado de pendientes*/

                /*Sync Button*/
                countSync();
            }
        }
    }

});

//==== BOTON SAVE ENCUESTA ====//


/*
 *              FORMULARIO INSTITUCION
 */

var InstitucionForm = Ext.create('Ext.form.Panel', {
    id: 'InstitucionForm',
    onCuitBlur: function() {
        // console.log(this);
    },
    autoScroll: true,
    //----para que resetee el dirty
    trackResetOnLoad: true,
    layout: {
        type: 'vbox',
        align: 'stretch'  // Child items are stretched to full width
    },
    margin: '5 5 5 5',
    defaultType: 'textfield',
    fieldDefaults: {
        cls: 'input',
        style: {
            'font-size': '13px'
        }
    },
    items: [{
            fieldLabel: 'ID',
            name: 'id',
            readOnly: true,
            xtype: 'hidden'
        }, mymap,
        /*
         {
         id: 'CUIT',
         // fieldLabel: 'CUIT',
         minLength: 13,
         maxLength: 13,
         margin: '10 0 0 0',
         name: '1695',
         regex: /[0-9]{2}-[0-9]{8}-[0-9]{1}/,
         regexText: "CUIT Inv&aacute;lido",
         allowBlank: false,
         vtype: 'CUIT', // applies custom 'IPAddress' validation rules to this field
         emptyText: 'Ingrese un Nro de CUIT valido',
         listeners: {
         blur: SearchInstitucion
         }
         }*/
        {
            xtype: 'combobox',
            emptyText: 'AGREGAR / SELECCIONAR INSTITUCION',
            queryMode: 'local',
            typeAhead: true,
            store: InstitucionStore,
            displayField: '4896',
            valueField: 'id',
            listeners: {
                select: function(combo, records, eOpts) {
                    InstitucionForm.loadRecord(records[0]);

                    var cuitValue = Ext.ComponentQuery.query('#inpt')[0].getValue();
                    
                     if (cuitValue != "") {
                     VisitasStoreInst.cuitFilter(cuitValue);
                     } else {
                     VisitasStoreInst.cuitFilter('-1');
                     }

                }
            }
        }
        , {
            xtype: 'fieldset',
            title: 'DATOS INSTITUCION',
            collapsible: false,
            defaultType: 'textfield',
            layout: 'anchor',
            defaults: {
                anchor: '100%'
            },
            items: [{
                    emptyText: 'Nombre',
                    name: '4896',
                    id: 'inpt',
                },
                {
                    xtype: 'radiogroup',
                    fieldLabel: 'Tipo de Institucion',
                    labelWidth: 400,
                    columns: 1,
                    items: [
                        {
                            boxLabel: 'Municipal',
                            name: '8104',
                            inputValue: 1
                        },
                        {
                            boxLabel: 'Provincial',
                            name: '8104',
                            inputValue: 2
                        },
                        {
                            boxLabel: 'Nacional',
                            name: '8104',
                            inputValue: 3
                        },
                        {
                            boxLabel: 'Otro',
                            name: '1694',
                            inputValue: -1
                        }
                    ]
                },
                {
                    id: 'ProvinciaCombo',
                    xtype: 'combobox',
                    name: '4897',
                    //fieldLabel: 'Provincia',
                    store: ProvinciaStore,
                    queryMode: 'local',
                    editable: false,
                    displayField: 'text',
                    valueField: 'value',
                    emptyText: 'Seleccione la Provincia',
                    allowBlank: false,
                    listeners: {
                        change: function(me, newValue, oldValue, eOpts) {
                            //if (newValue != null) {
                            if (me.value != null) {
                                PartidoStore.clearFilter();
                                /*
                                 PartidoStore.filters.removeAtKey('idrel');
                                 var myfilter = new Ext.util.Filter({
                                 filterFn: function(rec, anymatch) {
                                 return rec.get('idrel').indexOf(newValue.substr(0, 3)) > -1;
                                 }
                                 });
                                 PartidoStore.filter(myfilter);
                                 */
                                PartidoStore.filter('idrel', me.value);
                            }
                        }
                    }
                }
                ,
                {
                    id: 'PartidoCombo',
                    xtype: 'combobox',
                    name: '8102',
                    //fieldLabel: 'Partido',
                    store: PartidoStore,
                    queryMode: 'local',
                    editable: false,
                    displayField: 'text',
                    valueField: 'value',
                    emptyText: 'Seleccione el Partido'
                            //,editable: false

                },
                {
                    emptyText: 'Localidad',
                    name: '8103'
                },
                {
                    emptyText: 'Codigo Postal',
                    name: '8115'
                },
                {
                    emptyText: 'Calle / Ruta',
                    name: '8112'
                },
                {
                    emptyText: 'Nro. / Km.',
                    name: '8106'
                },
                {
                    emptyText: 'Piso',
                    name: '8113'
                },
                {
                    emptyText: 'Dto / Oficina',
                    name: '8114'
                }, {
                    emptyText: 'Telefonos',
                    name: '8108'
                }, {
                    emptyText: 'E-mail',
                    name: '6196',
                    vtype: 'email'
                }, {
                    emptyText: 'Pagina Web',
                    name: '8111'
                },
                {
                    xtype: 'hidden',
                    name: '8109',
                    id: 'lat',
                    readOnly: true
                },
                {
                    xtype: 'hidden',
                    name: '8110',
                    id: 'long',
                    readOnly: true
                },
                {
                    xtype: 'displayfield',
                    id: 'longLayDisplay',
                    style: {
                        fontSize: '11px',
                        color: 'blue',
                        padding: '4px'
                    }
                },
                {
                    id: 'task',
                    name: 'task',
                    xtype: 'hidden'
                }]
        },
        {
            xtype: 'fieldset',
            title: 'CONTACTO',
            collapsible: false,
            defaultType: 'textfield',
            layout: 'anchor',
            defaults: {
                anchor: '100%'
            },
            items: [
                {
                    emptyText: 'Apellido y Nombre',
                    name: '8105',
                },
                {
                    emptyText: 'cargo',
                    name: '8107',
                },
                {
                    emptyText: 'telefono',
                    name: '8117',
                },
                {
                    emptyText: 'E-mail',
                    name: '8116',
                    vtype: 'email'
                }
            ]
        }
    ],
    listeners: {
        afterRender: function(form) {
            params = Ext.urlDecode(location.search.substring(1));
            this.params = params;
            //console.log('Params:', params);
            InstitucionStore.load();
            //---creo un record vacio
            InstitucionForm.loadRecord(Ext.create('InstitucionModel', {}));

            //carga la tarea si existe
            if (InstitucionForm.params['task'] != null)
                Ext.getCmp('task').setValue(InstitucionForm.params['task']);
        },
        dirtychange: function(form) {
            /*Sync Button*/
            countSync();
            if (!InstitucionStore.isLoading())
                InstitucionForm.setLoading(false);
            if (form.isDirty()) {
                Ext.getCmp('btn_save').enable();
            } else {
                Ext.getCmp('btn_save').disable();
            }
        }
    },
    tbar: [
        btnNew,
        btnMap,
        btnMapManual,
        '->',
        //btnSave        
        btnSync
    ],
    bbar: [
        //btnSync
        btnSave
    ]
});

/*
 *              FORMULARIO VISITAS
 */

var VisitaForm = Ext.create('Ext.form.Panel', {
    id: 'VisitaForm',
    autoScroll: true,
    layout: {
        type: 'vbox',
        align: 'stretch'  // Child items are stretched to full width
    },
    margin: '5 5 5 5',
    defaultType: 'textfield',
    fieldDefaults: {
        cls: 'input',
        style: {
            'font-size': '13px'
        }
    },
    items: [{
            name: 'fecha',
            xtype: 'datefield',
            //submitFormat: 'Y-m-d',
            tooltip: 'Fecha de la Visita',
            emptyText: 'Fecha de la Visita',
            editable: false,
            allowBlank: false,
            maxValue: new Date()  // limited to the current date or prior
        }, {
            xtype: 'checkboxgroup',
            fieldLabel: 'Programas Informados',
            labelWidth: 150,
            padding: '0 0 20 0',
            columns: 2,
            items: [
                {
                    boxLabel: 'PACC',
                    name: '7898',
                    inputValue: '05'
                },
                {
                    boxLabel: 'Cr&eacute;dito Fiscal para Capacitaci&oacute;n',
                    name: '7898',
                    inputValue: '10'
                },
                {
                    boxLabel: 'Expertos PYME',
                    name: '7898',
                    inputValue: '15'
                },
                {
                    boxLabel: 'Grupos PYME',
                    name: '7898',
                    inputValue: '20'
                },
                {
                    boxLabel: 'Fonapyme',
                    name: '7898',
                    inputValue: '25'
                },
                {
                    boxLabel: 'R&eacute;gimen de Bonificaci&oacute;n de Tasas',
                    name: '7898',
                    inputValue: '30'
                },
                {
                    boxLabel: 'Mi Galp&oacute;n',
                    name: '7898',
                    inputValue: '35'
                },
                {
                    boxLabel: 'Nexo Pyme',
                    name: '7898',
                    inputValue: '40'
                },
                {
                    boxLabel: 'SGR',
                    name: '7898',
                    inputValue: '45'
                },
                {
                    boxLabel: 'Parques Industriales',
                    name: '7898',
                    inputValue: '50'
                },
                {
                    boxLabel: 'Programa del Bicentenario',
                    name: '7898',
                    inputValue: '55'
                },
                {
                    boxLabel: 'Capital Semilla',
                    name: '7898',
                    inputValue: '60'
                },
                {
                    boxLabel: 'Institucions Madrinas',
                    name: '7898',
                    inputValue: '65'
                },
                {
                    boxLabel: 'Proyectos de Desarrollo Local',
                    name: '7898',
                    inputValue: '70'
                }
            ]
        }, {
            id: 'notas',
            xtype: 'textarea',
            emptyText: 'Notas / Observaciones',
            name: 'nota',
            allowBlank: false
        }, {
            xtype: 'radiogroup',
            fieldLabel: 'Tipo de Visita',
            labelWidth: 150,
            columns: 2,
            items: [
                {
                    boxLabel: 'V&iacute;a Email',
                    name: 'tipovisita',
                    inputValue: 1
                },
                {
                    boxLabel: 'Llamado Telef&oacute;nico',
                    name: 'tipovisita',
                    inputValue: 2
                },
                {
                    boxLabel: 'Oficina Genia',
                    name: 'tipovisita',
                    inputValue: 3
                },
                {
                    boxLabel: 'Visita',
                    name: 'tipovisita',
                    inputValue: 4
                },
                {
                    boxLabel: 'Otro',
                    name: 'tipovisita',
                    inputValue: 5
                }

            ],
            listeners: {
                change: function(field, newValue, oldValue) {
                    var response = JSON.stringify(newValue);
                    if (response != null) {
                        if (response != '{"tipovisita":5}') {
                            Ext.getCmp('otros').hide();

                        } else {
                            Ext.getCmp('otros').show();
                        }

                    }
                }
            }
        }
        , {
            id: 'otros',
            xtype: 'textarea',
            emptyText: 'Especifique...',
            name: 'otros',
            hidden: true
        }
    ],
    listeners: {
        dirtychange: function(form) {
            /*Sync Button*/
            countSync();
            if (!InstitucionStore.isLoading())
                InstitucionForm.setLoading(false);
            if (form.isDirty()) {
                Ext.getCmp('btn_save_visita').enable();
            } else {
                Ext.getCmp('btn_save_visita').disable();
            }
        }
    }
    ,
    bbar: [
        btnSaveVisita
    ]
});

/*
 *              FORMULARIO ENCUESTAS
 */

var InstitucionFormPanel = Ext.create('Ext.Panel', {
    layout: 'fit',
    items: [InstitucionForm]
});

var VisitaFormPanel = Ext.create('Ext.Panel', {
    layout: 'fit',
    items: [VisitaForm]
});

// Cargo mi posicion en hiddens

function geoFindMe(map) {


    if (!navigator.geolocation) {
        Ext.getCmp('longLayDisplay').setValue("Geolocalización no soportada.");
        return;
    }

    function success(position) {
        var latitude = position.coords.latitude;
        // Ext.getCmp('mylat').setValue(latitude);


        var longitude = position.coords.longitude;
        //  Ext.getCmp('mylong').setValue(longitude);
        //map.panTo(new google.maps.LatLng(-34.6108797,-58.37546850000001));
        map.panTo(new google.maps.LatLng(latitude, longitude));
    }
    ;

    function error() {
        Ext.getCmp('longLayDisplay').setValue("No se puede determinar ubicación.");
        //    Ext.getCmp('mylat').setValue(0);
        //    Ext.getCmp('mylong').set 150.644Value(0);
    }
    ;

    //Ext.getCmp('longLayDisplay').setValue("<strong>Localizando...</strong>");

    navigator.geolocation.getCurrentPosition(success, error);

}


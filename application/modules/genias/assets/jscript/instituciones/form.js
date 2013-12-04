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
    var getCount = storeEncuestasOffline.getCount() + storeVisitaOffline.getCount() + storeInstitucionOffline.getCount() + storeVisitaOfflineDelete.getCount();
    Ext.getCmp('btnSync').setText('Hay (' + getCount + ') para actualizar');
}





var SearchInstitucion = function(me) {
    val = me.value
    if (me.isValid() && me.value.length == 13 && !InstitucionStore.isLoading()) {
        InstitucionForm.setLoading('Buscando...');
        actualRecord = InstitucionForm.getRecord();
        index = InstitucionStore.find('1695', val);

        if (index >= 0) {

            record = InstitucionStore.getAt(index);
            if (record != actualRecord) {
                InstitucionForm.loadRecord(record);
            }
            InstitucionForm.setLoading(false);


            /*ENCUESTA*/
            EncuestaForm.setLoading('Buscando...')
            actualRecordEncuesta = EncuestaForm.getRecord();
            indexEncuesta = EncuestasStore.find('cuit', val);
            recordEncuesta = EncuestasStore.getAt(indexEncuesta);
            if (recordEncuesta != actualRecordEncuesta) {
                EncuestaForm.loadRecord(recordEncuesta);
            }
            EncuestaForm.setLoading(false);

            /* Para tareas relacionadas via Agenda*/

            //---tomo parametros con Ext
            var params = Ext.urlDecode(location.search.substring(1));

            if (InstitucionForm.params['task'] != null) {
                Ext.getCmp('task').setValue(InstitucionForm.params['task']);
            }

        } else {
            record = Ext.create('InstitucionModel', {
                1695: val
            });
            InstitucionForm.loadRecord(record);
            InstitucionForm.setLoading(false);

        }

        var cuitValue = Ext.getCmp('CUIT').getValue();
        if (cuitValue != "") {
            VisitasStore.cuitFilter(cuitValue);
        } else {
            VisitasStore.cuitFilter('-1');
        }
        //carga tarea si existe
        if (InstitucionForm.params['task'] != null)
            Ext.getCmp('task').setValue(InstitucionForm.params['task']);

    }
};

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
            if (InstitucionStore.find('1695', record.get('1695')) == -1) {
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
    text: '<i class="icon icon-save"></i> Guardar datos Visita',
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
            if (dataInstitucion['1695']) {
                visitaRecord = Ext.create('visitaModel', {
                    fecha: data['fecha'], //n,
                    cuit: dataInstitucion['1695'],
                    nota: data['nota'],
                    tipo: data['tipovisita'],
                    otros: data['otros'],
                    7898: data['7898']
                });
                //--agrego al que se usa para visualizar    

                VisitasStore.add(visitaRecord);
                //---busco por cuit            
                //--agrego al que se usa para syncro y persistencia
                storeVisitaOffline.add(visitaRecord);
                storeVisitaOffline.sync();
                VisitasGrid.refresh();
                /*Actualizo listado de pendientes*/

                /*Sync Button*/
                countSync();
            }
        }
    }

});

//==== BOTON SAVE ENCUESTA ====//

var btnSaveEncuesta = Ext.create('Ext.Action', {
    id: 'btn_save_encuesta',
    disabled: true,
    xtype: 'button',
    text: '<i class="icon icon-save"></i> Guardar Encuesta',
    handler: function() {

        var formInstitucion = InstitucionForm;
        var recordInstitucion = formInstitucion.getRecord();
        var form = EncuestaForm;
        var record = form.getRecord();
        if (record) {
            //----es uno del grid
            form.getForm().updateRecord(record);
        }

        data = form.getValues();
        dataInstitucion = formInstitucion.getValues();
        var d = new Date();
        var n = d.toISOString();
        if (dataInstitucion['1695']) {
            encuestaRecord = Ext.create('encuestaModel', {
                fecha: n,
                cuit: dataInstitucion['1695'],
                7663: data['7663'],
                7664: data['7664'],
                7883: data['7883'],
                7886: data['7886'],
                7887: data['7887'],
                7888: data['7888'],
                7889: data['7889'],
                7890: data['7890'],
                7891: data['7891']
            });
            EncuestasStore.add(encuestaRecord);
            //---busco por cuit            
            //--agrego al que se usa para syncro y persistencia
            storeEncuestasOffline.add(encuestaRecord);
            storeEncuestasOffline.sync();
            /*Sync Button*/
            countSync();
        }
    }

});

/*
 *              FORMULARIO EMPRESAS
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
            fieldLabel: 'Seleccionar institucion',
            queryMode: 'local',
            typeAhead: true,
            store: InstitucionStore,
            displayField: '4896',
            valueField: 'id',
            listeners: {
                select: function(combo, records, eOpts) {
                    InstitucionForm.loadRecord(records[0]);
                },
                specialkey: function(field, e) {
                    // e.HOME, e.END, e.PAGE_UP, e.PAGE_DOWN,
                    // e.TAB, e.ESC, arrow keys: e.LEFT, e.RIGHT, e.UP, e.DOWN
                    if (e.getKey() == e.ENTER || e.getKey() == e.TAB) {
                        console.log('txt:', field.getValue(), field.findRecordByValue(field.getValue()));
                        record = field.findRecordByValue(field.getValue());
                        if (record) {
                            //    InstitucionForm.loadRecord(record);
                        }
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
                    name: '4896'
                },
                {
                    xtype: 'radiogroup',
                    fieldLabel: 'Tipo de Institucion',
                    labelWidth: 400,
                    columns: 1,
                    items: [
                        {
                            boxLabel: 'Municipal',
                            name: '1694',
                            inputValue: 14
                        },
                        {
                            boxLabel: 'Provincial',
                            name: '1694',
                            inputValue: 4
                        },
                        {
                            boxLabel: 'Nacional',
                            name: '1694',
                            inputValue: 15
                        },
                        {
                            boxLabel: 'Otro',
                            name: '1694',
                            inputValue: 8
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

                }, {
                    emptyText: 'Codigo Postal',
                    name: '1698'
                },
                {
                    emptyText: 'Calle / Ruta',
                    name: '4653'
                },
                {
                    emptyText: 'Nro. / Km.',
                    name: '4654'
                },
                {
                    emptyText: 'Piso',
                    name: '4655'
                },
                {
                    emptyText: 'Dto / Oficina',
                    name: '4656'
                }, {
                    emptyText: 'Telefonos',
                    name: '1701'
                }, {
                    emptyText: 'E-mail',
                    name: '1703',
                    vtype: 'email'
                }, {
                    emptyText: 'Pagina Web',
                    name: '1704'
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
            items: [{
                    emptyText: 'Apellido y Nombre',
                    name: '8105',
                }, {
                    emptyText: 'E-mail',
                    name: '6196',
                    vtype: 'email'
                }]
        }
    ],
    listeners: {
        afterRender: function(form) {
            params = Ext.urlDecode(location.search.substring(1));
            this.params = params;
            //console.log('Params:', params);
            if (params['cuit'] != null) {
                field = InstitucionForm.getForm().findField("1695");
                field.setValue(InstitucionForm.params['cuit']);
                //----me fijo si todavia est? cargando

                if (InstitucionStore.isLoading()) {
                    InstitucionForm.setLoading('cargando...');
                    InstitucionStore.on('load', function()
                    {
                        InstitucionForm.setLoading(false);
                        //console.log('ahora?');
                        SearchInstitucion(field);
                    });
                } else {
                    //----si ya cargo simplemente filtro
                    SearchInstitucion(field);

                }

            } else {
                InstitucionStore.load();
                //---creo un record vacio
                InstitucionForm.loadRecord(Ext.create('InstitucionModel', {}));
            }
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

var EncuestaForm = Ext.create('Ext.form.Panel', {
    id: 'EncuestaForm',
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
            xtype: 'fieldset',
            title: 'RESPONSABILIDAD SOCIAL',
            collapsible: false,
            defaultType: 'textfield',
            layout: 'anchor',
            defaults: {
                anchor: '100%'
            },
            items: [
                {
                    xtype: 'radiogroup',
                    fieldLabel: 'Ha realizado/a acciones vinculadas a la Responsabilidad Social',
                    labelWidth: 400,
                    padding: '0 0 20 0',
                    columns: 2,
                    items: [
                        {
                            boxLabel: 'No, pero hay interes en hacerlo',
                            name: '7663',
                            inputValue: 4
                        },
                        {
                            boxLabel: 'Si, en ambos periodos',
                            name: '7663',
                            inputValue: 3
                        },
                        {
                            boxLabel: 'Si, en a&ntilde;os anteriores',
                            name: '7663',
                            inputValue: 2
                        },
                        {
                            boxLabel: 'Si, en la actualidad',
                            name: '7663',
                            inputValue: 1
                        },
                        {
                            boxLabel: 'No',
                            name: '7663',
                            inputValue: 5
                        }
                    ],
                    listeners: {
                        change: function(field, newValue, oldValue) {
                            var response = JSON.stringify(newValue);
                            if (response != null) {
                                if (response == '{"7663":5}') {
                                    Ext.getCmp('field7664').hide();

                                } else {
                                    Ext.getCmp('field7664').show();
                                }

                            }
                        }
                    }
                },
                {
                    hidden: true,
                    id: 'field7664',
                    xtype: 'radiogroup',
                    //fieldLabel: 'Existe articulaci&oacute;n de las acciones con organismos gubernamentales',
                    fieldLabel: 'Tienen relaci&oacute;n con organismos gubernamentales',
                    labelWidth: 400,
                    padding: '0 0 20 0',
                    items: [
                        {
                            boxLabel: 'SI',
                            name: '7664',
                            inputValue: 1
                        },
                        {
                            boxLabel: 'NO',
                            name: '7664',
                            inputValue: 2
                        },
                    ]
                }, {
                    xtype: 'radiogroup',
                    fieldLabel: 'Registro Unico de Organizaciones de Responsabilidad Social',
                    labelWidth: 400,
                    padding: '0 0 20 0',
                    columns: 2,
                    items: [
                        {
                            boxLabel: 'SI',
                            name: '7883',
                            inputValue: 'si'
                        },
                        {
                            boxLabel: 'NO',
                            name: '7883',
                            inputValue: 'no'
                        },
                        {
                            boxLabel: 'No Sabe/No contesta',
                            name: '7883',
                            inputValue: 'nc'
                        }
                    ]
                }]
        }, {
            xtype: 'fieldset',
            title: 'FINANCIAMIENTO',
            collapsible: false,
            defaultType: 'textfield',
            layout: 'anchor',
            defaults: {
                anchor: '100%'
            },
            items: [
                {
                    xtype: 'checkboxgroup',
                    fieldLabel: 'Modos de Financiamiento',
                    labelWidth: 400,
                    padding: '0 0 20 0',
                    columns: 2,
                    items: [
                        {
                            boxLabel: 'Proovedores',
                            name: '7886',
                            inputValue: '01'
                        },
                        {
                            boxLabel: 'Bancos',
                            name: '7886',
                            inputValue: '02'
                        },
                        {
                            boxLabel: 'Programas Asistencia Provincial',
                            name: '7886',
                            inputValue: '03'
                        },
                        {
                            boxLabel: 'Programas Asistencia Municipal',
                            name: '7886',
                            inputValue: '04'
                        },
                        {
                            boxLabel: 'Otros programas nacionales',
                            name: '7886',
                            inputValue: '05'
                        },
                    ]
                }, {
                    xtype: 'checkboxgroup',
                    fieldLabel: 'Con Programas Sepyme/Ministerio de Industria',
                    labelWidth: 400,
                    columns: 2,
                    items: [
                        {
                            boxLabel: 'Fonapyme',
                            name: '7887',
                            inputValue: 10
                        },
                        {
                            boxLabel: 'R&eacute;gimen de Bonificaci&oacute;n de Tasas',
                            name: '7887',
                            inputValue: 20
                        },
                        {
                            boxLabel: 'Mi Galp&oacute;n',
                            name: '7887',
                            inputValue: 30
                        },
                        {
                            boxLabel: 'Nexo Pyme',
                            name: '7887',
                            inputValue: 40
                        },
                        {
                            boxLabel: 'SGR',
                            name: '7887',
                            inputValue: 50
                        },
                        {
                            boxLabel: 'Parques Industriales',
                            name: '7887',
                            inputValue: 60
                        }
                    ]
                }]
        }, {
            xtype: 'fieldset',
            title: 'CAPACITACION Y ASISTENCIA TECNICA',
            collapsible: false,
            defaultType: 'textfield',
            layout: 'anchor',
            defaults: {
                anchor: '100%'
            },
            items: [
                {
                    xtype: 'radiogroup',
                    fieldLabel: 'Recibi&oacute; Capacitaci&oacute;n Institucionrial/Gerencial/Mandos Medios',
                    padding: '0 0 20 0',
                    labelWidth: 400,
                    items: [
                        {
                            boxLabel: 'SI',
                            name: '7888',
                            inputValue: 1
                        },
                        {
                            boxLabel: 'NO',
                            name: '7888',
                            inputValue: 2
                        },
                    ]
                }, {
                    xtype: 'radiogroup',
                    fieldLabel: 'Realiz&oacute; capacitaciones al personal',
                    padding: '0 0 20 0',
                    labelWidth: 400,
                    items: [
                        {
                            boxLabel: 'SI',
                            name: '7889',
                            inputValue: 1
                        },
                        {
                            boxLabel: 'NO',
                            name: '7889',
                            inputValue: 2
                        },
                    ]
                }, {
                    xtype: 'radiogroup',
                    fieldLabel: 'Recibi&oacute; asesoramiento t&eacute;cnico',
                    padding: '0 0 20 0',
                    labelWidth: 400,
                    items: [
                        {
                            boxLabel: 'SI',
                            name: '7890',
                            inputValue: 1
                        },
                        {
                            boxLabel: 'NO',
                            name: '7890',
                            inputValue: 2
                        },
                    ]
                }, {
                    xtype: 'checkboxgroup',
                    fieldLabel: ' Capacitaci&oacute;n/Asistencia Sepyme/Ministerio de Industria ',
                    padding: '0 0 20 0',
                    labelWidth: 400,
                    columns: 1,
                    items: [
                        {
                            boxLabel: 'Cr&eacute;dito Fiscal para Capacitaci&oacute;n',
                            name: '7891',
                            inputValue: '10'
                        },
                        {
                            boxLabel: 'Cr&eacute;dito Fiscal para Capacitaci&oacute;n',
                            name: '7891',
                            inputValue: '20'
                        },
                        {
                            boxLabel: 'PACC',
                            name: '7891',
                            inputValue: '30'
                        },
                        {
                            boxLabel: 'Expertos PYME',
                            name: '7891',
                            inputValue: '40'
                        },
                        {
                            boxLabel: 'Grupos PYME',
                            name: '7891',
                            inputValue: '50'
                        },
                    ]
                }
            ]
        }
    ],
    listeners: {
        dirtychange: function(form) {
            /*Sync Button*/
            countSync();
            if (!InstitucionStore.isLoading())
                InstitucionForm.setLoading(false);
            if (form.isDirty()) {
                Ext.getCmp('btn_save_encuesta').enable();
            } else {
                Ext.getCmp('btn_save_encuesta').disable();
            }
        }
    }
    ,
    bbar: [
        btnSaveEncuesta
    ]
});

var InstitucionFormPanel = Ext.create('Ext.Panel', {
    layout: 'fit',
    items: [InstitucionForm]
});

var VisitaFormPanel = Ext.create('Ext.Panel', {
    layout: 'fit',
    items: [VisitaForm]
});

var EncuestaFormPanel = Ext.create('Ext.Panel', {
    layout: 'fit',
    items: [EncuestaForm]
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


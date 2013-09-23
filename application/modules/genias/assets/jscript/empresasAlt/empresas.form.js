var countSync = function() {
    var getCount = storeEncuestasOffline.getCount() + storeVisitaOffline.getCount() + storeEmpresaOffline.getCount()+ storeVisitaOfflineDelete.getCount();
    Ext.getCmp('btnSync').setText('Hay (' + getCount + ') para actualizar');
}


var SearchEmpresa = function(me) {
    val = me.value
    if (me.isValid() && me.value.length == 13 && !EmpresaStore.isLoading()) {
        EmpresaForm.setLoading('Buscando...');
        actualRecord = EmpresaForm.getRecord();
        index = EmpresaStore.find('1695', val);

        if (index >= 0) {

            record = EmpresaStore.getAt(index);
            if (record != actualRecord) {
                EmpresaForm.loadRecord(record);
            }
            EmpresaForm.setLoading(false);


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

            if (EmpresaForm.params['task'] != null) {
                Ext.getCmp('task').setValue(EmpresaForm.params['task']);
            }

        } else {
            record = Ext.create('EmpresaModel', {1695:val});
            EmpresaForm.loadRecord(record);
            EmpresaForm.setLoading(false);

        }

        var cuitValue = Ext.getCmp('CUIT').getValue();
        if (cuitValue != "") {
            VisitasStore.cuitFilter(cuitValue);
        } else {
            VisitasStore.cuitFilter('-1');
        }
        //carga tarea si existe
        if (EmpresaForm.params['task'] != null)
            Ext.getCmp('task').setValue(EmpresaForm.params['task']);

    }
};

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
var btnNew = Ext.create('Ext.Action', {
    id: 'btn_new',
    xtype: 'button',
    text: '<i class="icon icon-plus"></i> Agregar',
    handler: function() {
        EmpresaForm.loadRecord(Ext.create('EmpresaModel', {}));
        /*Reseteo si hubiera una tarea asociada anterio*/
        EmpresaForm.params['task'] = null;
        Ext.getCmp('task').setValue("");
    }
});


var btnSave = Ext.create('Ext.Action', {
    id: 'btn_save',
    disabled: true,
    xtype: 'button',
    text: '<i class="icon icon-save"></i> Guardar datos Empresa',
    handler: function() {
        var form = EmpresaForm;
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
            if (EmpresaStore.find('1695', record.get('1695')) == -1) {
                //---si no estaba lo agrego al online
                EmpresaStore.add(record);
            }
            storeEmpresaOffline.add(record);
            storeEmpresaOffline.sync();


            /*Sync Button*/
            countSync();
        }
    }

});

var btnSaveVisita = Ext.create('Ext.Action', {
    id: 'btn_save_visita',
    disabled: true,
    xtype: 'button',
    text: '<i class="icon icon-save"></i> Guardar datos Visita',
    handler: function() {
        var form = VisitaForm;
        var formEmpresa = EmpresaForm;
        if (!form.isValid()) {
            Ext.Msg.alert('Encenario Pyme', '<h5>Complete los campos correctamente</h5>');
        } else {
            var recordEmpresa = formEmpresa.getRecord();
            var record = form.getRecord();
            if (record) {
                //----es uno del grid
                form.getForm().updateRecord(record);
            }
            data = form.getValues();
            dataEmpresa = formEmpresa.getValues();
            //var d = data['fecha']; //new Date();            
            //var n = d.toISOString();            
            if (dataEmpresa['1695']) {
                visitaRecord = Ext.create('visitaModel', {
                    fecha: data['fecha'], //n,
                    cuit: dataEmpresa['1695'],
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

var btnSaveEncuesta = Ext.create('Ext.Action', {
    id: 'btn_save_encuesta',
    disabled: true,
    xtype: 'button',
    text: '<i class="icon icon-save"></i> Guardar Encuesta',
    handler: function() {

        var formEmpresa = EmpresaForm;
        var recordEmpresa = formEmpresa.getRecord();
        var form = EncuestaForm;
        var record = form.getRecord();
        if (record) {
            //----es uno del grid
            form.getForm().updateRecord(record);
        }

        data = form.getValues();
        dataEmpresa = formEmpresa.getValues();
        var d = new Date();
        var n = d.toISOString();
        if (dataEmpresa['1695']) {
            encuestaRecord = Ext.create('encuestaModel', {
                fecha: n,
                cuit: dataEmpresa['1695'],
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

var EmpresaForm = Ext.create('Ext.form.Panel', {
    id: 'EmpresaForm',
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
        },
        {
            id: 'CUIT',
            // fieldLabel: 'CUIT',
            minLength: 13,
            maxLength: 13,
            name: '1695',
            regex: /[0-9]{2}-[0-9]{8}-[0-9]{1}/,
            regexText: "CUIT Inv&aacute;lido",
            allowBlank: false,
            vtype: 'CUIT', // applies custom 'IPAddress' validation rules to this field
            emptyText: 'Ingrese un Nro de CUIT valido',
            listeners: {
                blur: SearchEmpresa
            }
        }, {
            xtype: 'fieldset',
            title: 'DATOS EMPRESA',
            collapsible: false,
            defaultType: 'textfield',
            layout: 'anchor',
            defaults: {
                anchor: '100%'
            },
            items: [{
                    emptyText: 'Nombre',
                    name: '1693'
                },
                {
                    xtype: 'radiogroup',
                    fieldLabel: 'Tipo de Empresa',
                    labelWidth: 400,
                    columns: 1,
                    items: [
                        {boxLabel: 'Sucursal Empresa Extranjera', name: '1694', inputValue: 14},
                        {boxLabel: 'Colectiva', name: '1694', inputValue: 4},
                        {boxLabel: 'U.T.E', name: '1694', inputValue: 15},
                        {boxLabel: 'Unipersonal', name: '1694', inputValue: 8},
                        {boxLabel: 'Sociedad de Responsabilidad Limitada (S.R.L)', name: '1694', inputValue: 2},
                        {boxLabel: 'Sociedad De Hecho (S.H)', name: '1694', inputValue: 7},
                        {boxLabel: 'Sociedad Anonima (S.A)', name: '1694', inputValue: 1},
                        {boxLabel: 'Cooperativa', name: '1694', inputValue: 12},
                        {boxLabel: 'Sociedad en Comandita', name: '1694', inputValue: 24}
                    ]
                },
                {
                    id: 'ProvinciaCombo',
                    xtype: 'combobox',
                    name: '4651',
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
                    name: '1699',
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
                }, {
                    emptyText: 'Cantidad de Empleados actual',
                    name: '1711'
                }, {
                    emptyText: 'Rubro de la Empresa',
                    name: '7878'
                },
                {
                    xtype: 'hidden',
                    name: '7819',
                    id: 'long',
                    readOnly: true
                },
                {
                    xtype: 'hidden',
                    name: '7820',
                    id: 'lat',
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
                    name: '7876',
                }, {
                    emptyText: 'E-mail',
                    name: '7877',
                    vtype: 'email'
                }]
        }, {
            xtype: 'fieldset',
            title: 'PLANTA',
            collapsible: false,
            defaultType: 'textfield',
            layout: 'anchor',
            defaults: {
                anchor: '100%'
            },
            items: [{
                    emptyText: 'Superficie Cubierta',
                    name: '7879',
                }, {
                    xtype: 'radiogroup',
                    fieldLabel: 'Posesion',
                    labelWidth: 400,
                    items: [
                        {boxLabel: 'Alquilado', name: '7880', inputValue: 20},
                        {boxLabel: 'Propio', name: '7880', inputValue: 10},
                    ]
                }]
        }, {
            xtype: 'fieldset',
            title: 'PRODUCCION',
            collapsible: false,
            defaultType: 'textfield',
            layout: 'anchor',
            defaults: {
                anchor: '100%'
            },
            items: [, {
                    emptyText: 'Productos o servicios que Ofrece',
                    name: '1715',
                    xtype: 'textarea'
                }, {
                    xtype: 'radiogroup',
                    fieldLabel: 'Tiene componentes importados?',
                    padding: '0 0 20 0',
                    labelWidth: 400,
                    items: [
                        {boxLabel: 'SI', name: '7881', inputValue: 1},
                        {boxLabel: 'NO', name: '7881', inputValue: 2},
                    ]
                }, {
                    xtype: 'radiogroup',
                    fieldLabel: 'Pueden ser reemplazados?',
                    padding: '0 0 20 0',
                    labelWidth: 400,
                    items: [
                        {boxLabel: 'SI', name: '7882', inputValue: 1},
                        {boxLabel: 'NO', name: '7882', inputValue: 2},
                    ]
                }, {
                    xtype: 'radiogroup',
                    fieldLabel: 'Tiene capacidad para exportar?',
                    padding: '0 0 20 0',
                    labelWidth: 400,
                    items: [
                        {boxLabel: 'SI', name: '7883', inputValue: 1},
                        {boxLabel: 'NO', name: '7883', inputValue: 2},
                    ]
                },
                {
                    xtype: 'checkboxgroup',
                    fieldLabel: 'Mercado destino',
                    padding: '0 0 20 0',
                    labelWidth: 400,
                    columns: 2,
                    items: [
                        {boxLabel: 'A otras provincias', name: '1716', inputValue: '3'},
                        {boxLabel: 'Dentro de la provincia', name: '1716', inputValue: '1'},
                        {boxLabel: 'Mercosur', name: '1716', inputValue: '4'},
                        {boxLabel: 'Internacional', name: '1716', inputValue: '5'},
                    ]
                }
                , {
                    emptyText: 'Proveedores',
                    name: '7884'

                }]
        }
    ],
    listeners: {
        afterRender: function(form) {
            params = Ext.urlDecode(location.search.substring(1));
            this.params = params;
            //console.log('Params:', params);
            if (params['cuit'] != null) {
                field = EmpresaForm.getForm().findField("1695");
                field.setValue(EmpresaForm.params['cuit']);
                //----me fijo si todavia est? cargando

                if (EmpresaStore.isLoading()) {
                    EmpresaForm.setLoading('cargando...');
                    EmpresaStore.on('load', function()
                    {
                        EmpresaForm.setLoading(false);
                        //console.log('ahora?');
                        SearchEmpresa(field);
                    });
                } else {
                    //----si ya cargo simplemente filtro
                    SearchEmpresa(field);

                }

            } else {
                EmpresaStore.load();
                //---creo un record vacio
                EmpresaForm.loadRecord(Ext.create('EmpresaModel', {}));
            }
            //carga la tarea si existe
            if (EmpresaForm.params['task'] != null)
                Ext.getCmp('task').setValue(EmpresaForm.params['task']);
        },
        dirtychange: function(form) {
            /*Sync Button*/
            countSync();
            if (!EmpresaStore.isLoading())
                EmpresaForm.setLoading(false);
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
        '->',
        //btnSave        
        btnSync
    ],
    bbar: [
        //btnSync
        btnSave
    ]
});


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
                {boxLabel: 'PACC', name: '7898', inputValue: '05'},
                {boxLabel: 'Cr&eacute;dito Fiscal para Capacitaci&oacute;n', name: '7898', inputValue: '10'},
                {boxLabel: 'Expertos PYME', name: '7898', inputValue: '15'},
                {boxLabel: 'Grupos PYME', name: '7898', inputValue: '20'},
                {boxLabel: 'Fonapyme', name: '7898', inputValue: '25'},
                {boxLabel: 'R&eacute;gimen de Bonificaci&oacute;n de Tasas', name: '7898', inputValue: '30'},
                {boxLabel: 'Mi Galp&oacute;n', name: '7898', inputValue: '35'},
                {boxLabel: 'Nexo Pyme', name: '7898', inputValue: '40'},
                {boxLabel: 'SGR', name: '7898', inputValue: '45'},
                {boxLabel: 'Parques Industriales', name: '7898', inputValue: '50'},
                {boxLabel: 'Programa del Bicentenario', name: '7898', inputValue: '55'},
                {boxLabel: 'Capital Semilla', name: '7898', inputValue: '60'},
                {boxLabel: 'Empresas Madrinas', name: '7898', inputValue: '65'},
                {boxLabel: 'Proyectos de Desarrollo Local', name: '7898', inputValue: '70'}
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
                {boxLabel: 'V&iacute;a Email', name: 'tipovisita', inputValue: 1},
                {boxLabel: 'Llamado Telef&oacute;nico', name: 'tipovisita', inputValue: 2},
                {boxLabel: 'Oficina Genia', name: 'tipovisita', inputValue: 3},
                {boxLabel: 'Visita', name: 'tipovisita', inputValue: 4},
                {boxLabel: 'Otro', name: 'tipovisita', inputValue: 5}

            ], listeners: {
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
            if (!EmpresaStore.isLoading())
                EmpresaForm.setLoading(false);
            if (form.isDirty()) {
                Ext.getCmp('btn_save_visita').enable();
            } else {
                Ext.getCmp('btn_save_visita').disable();
            }
        }
    }
    , bbar: [
        btnSaveVisita
    ]
});


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
                        {boxLabel: 'No, pero hay interes en hacerlo', name: '7663', inputValue: 4},
                        {boxLabel: 'Si, en ambos periodos', name: '7663', inputValue: 3},
                        {boxLabel: 'Si, en a&ntilde;os anteriores', name: '7663', inputValue: 2},
                        {boxLabel: 'Si, en la actualidad', name: '7663', inputValue: 1},
                        {boxLabel: 'No', name: '7663', inputValue: 5}
                    ], listeners: {
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
                        {boxLabel: 'SI', name: '7664', inputValue: 1},
                        {boxLabel: 'NO', name: '7664', inputValue: 2},
                    ]
                }, {
                    xtype: 'radiogroup',
                    fieldLabel: 'Registro Unico de Organizaciones de Responsabilidad Social',
                    labelWidth: 400,
                    padding: '0 0 20 0',
                    columns: 2,
                    items: [
                        {boxLabel: 'SI', name: '7883', inputValue: 'si'},
                        {boxLabel: 'NO', name: '7883', inputValue: 'no'},
                        {boxLabel: 'No Sabe/No contesta', name: '7883', inputValue: 'nc'}
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
                        {boxLabel: 'Proovedores', name: '7886', inputValue: '01'},
                        {boxLabel: 'Bancos', name: '7886', inputValue: '02'},
                        {boxLabel: 'Programas Asistencia Provincial', name: '7886', inputValue: '03'},
                        {boxLabel: 'Programas Asistencia Municipal', name: '7886', inputValue: '04'},
                        {boxLabel: 'Otros programas nacionales', name: '7886', inputValue: '05'},
                    ]
                }, {
                    xtype: 'checkboxgroup',
                    fieldLabel: 'Con Programas Sepyme/Ministerio de Industria',
                    labelWidth: 400,
                    columns: 2,
                    items: [
                        {boxLabel: 'Fonapyme', name: '7887', inputValue: 10},
                        {boxLabel: 'R&eacute;gimen de Bonificaci&oacute;n de Tasas', name: '7887', inputValue: 20},
                        {boxLabel: 'Mi Galp&oacute;n', name: '7887', inputValue: 30},
                        {boxLabel: 'Nexo Pyme', name: '7887', inputValue: 40},
                        {boxLabel: 'SGR', name: '7887', inputValue: 50},
                        {boxLabel: 'Parques Industriales', name: '7887', inputValue: 60}
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
                    fieldLabel: 'Recibi&oacute; Capacitaci&oacute;n Empresarial/Gerencial/Mandos Medios',
                    padding: '0 0 20 0',
                    labelWidth: 400,
                    items: [
                        {boxLabel: 'SI', name: '7888', inputValue: 1},
                        {boxLabel: 'NO', name: '7888', inputValue: 2},
                    ]
                }, {
                    xtype: 'radiogroup',
                    fieldLabel: 'Realiz&oacute; capacitaciones al personal',
                    padding: '0 0 20 0',
                    labelWidth: 400,
                    items: [
                        {boxLabel: 'SI', name: '7889', inputValue: 1},
                        {boxLabel: 'NO', name: '7889', inputValue: 2},
                    ]
                }, {
                    xtype: 'radiogroup',
                    fieldLabel: 'Recibi&oacute; asesoramiento t&eacute;cnico',
                    padding: '0 0 20 0',
                    labelWidth: 400,
                    items: [
                        {boxLabel: 'SI', name: '7890', inputValue: 1},
                        {boxLabel: 'NO', name: '7890', inputValue: 2},
                    ]
                }, {
                    xtype: 'checkboxgroup',
                    fieldLabel: ' Capacitaci&oacute;n/Asistencia Sepyme/Ministerio de Industria ',
                    padding: '0 0 20 0',
                    labelWidth: 400,
                    columns: 1,
                    items: [
                        {boxLabel: 'Cr&eacute;dito Fiscal para Capacitaci&oacute;n', name: '7891', inputValue: '10'},
                        {boxLabel: 'Cr&eacute;dito Fiscal para Capacitaci&oacute;n', name: '7891', inputValue: '20'},
                        {boxLabel: 'PACC', name: '7891', inputValue: '30'},
                        {boxLabel: 'Expertos PYME', name: '7891', inputValue: '40'},
                        {boxLabel: 'Grupos PYME', name: '7891', inputValue: '50'},
                    ]
                }
            ]
        }
    ],
    listeners: {
        dirtychange: function(form) {
            /*Sync Button*/
            countSync();
            if (!EmpresaStore.isLoading())
                EmpresaForm.setLoading(false);
            if (form.isDirty()) {
                Ext.getCmp('btn_save_encuesta').enable();
            } else {
                Ext.getCmp('btn_save_encuesta').disable();
            }
        }
    }
    , bbar: [
        btnSaveEncuesta
    ]
});

var EmpresaFormPanel = Ext.create('Ext.Panel', {
    layout: 'fit',
    items: [EmpresaForm]
});

var VisitaFormPanel = Ext.create('Ext.Panel', {
    layout: 'fit',
    items: [VisitaForm]
});

var EncuestaFormPanel = Ext.create('Ext.Panel', {
    layout: 'fit',
    items: [EncuestaForm]
});


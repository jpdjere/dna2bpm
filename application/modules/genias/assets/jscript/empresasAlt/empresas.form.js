var countSync = function() {
    var getCount = storeVisitaOffline.getCount() + storeEmpresaOffline.getCount();
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

            /* Para tareas relacionadas via Agenda*/

            //---tomo parametros con Ext
            var params = Ext.urlDecode(location.search.substring(1));

            if (EmpresaForm.params['task'] != null)
                Ext.getCmp('task').setValue(EmpresaForm.params['task']);

        } else {
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
        var record = form.getRecord();
        if (record) {
            //----es uno del grid
            form.getForm().updateRecord(record);
        }
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

});

var btnSaveVisita = Ext.create('Ext.Action', {
    id: 'btn_save_visita',
    disabled: true,
    xtype: 'button',
    text: '<i class="icon icon-save"></i> Guardar datos Visita',
    handler: function() {

        var formEmpresa = EmpresaForm;
        var recordEmpresa = formEmpresa.getRecord();

        var form = VisitaForm;

        var record = form.getRecord();
        if (record) {
            //----es uno del grid
            form.getForm().updateRecord(record);
        }
        data = form.getValues();
        dataEmpresa = formEmpresa.getValues();
        var d = new Date();
        var n = d.toISOString();
        if (data['7408']) {
            visitaRecord = Ext.create('visitaModel', {
                fecha: n,
                cuit: dataEmpresa['1695'],
                nota: data['7408'],
                tipo: data['tipovisita'],
                otros: data['otros']
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
            collapsible: true,
            collapsed: true,
            defaultType: 'textfield',
            layout: 'anchor',
            defaults: {
                anchor: '100%'
            },
            items: [{                    
                    emptyText: 'Nombre',
                    name: '1693'
                }, {
                    emptyText: 'Tipo de empresa',
                    name: '1694'
                },
                {
                    id: 'ProvinciaCombo',
                    xtype: 'combobox',
                    name: '4651',
                    //fieldLabel: 'Provincia',
                    store: ProvinciaStore,
                    queryMode: 'local',
                    displayField: 'text',
                    valueField: 'value',
                    emptyText: 'Seleccione la Provincia',
                    listeners: {
                        change: function(me, newValue, oldValue, eOpts) {
                            if (newValue != null) {
                                PartidoStore.clearFilter();
                                PartidoStore.filters.removeAtKey('idrel');
                                var myfilter = new Ext.util.Filter({
                                    filterFn: function(rec, anymatch) {
                                        return rec.get('idrel').indexOf(newValue.substr(0, 3)) > -1;
                                    }
                                });
                                PartidoStore.filter(myfilter);
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
                    emptyText: 'Tel&eacute;fonos',
                    name: '1701'
                }, {
                    emptyText: 'E-mail',
                    name: '1703',
                    vtype: 'email',
                    tooltip: 'Enter your email address'
                }, {
                    emptyText: 'P&aacute;gina Web',
                    name: '1704'
                }, {
                    emptyText: 'Cantidad de Empleados actual',
                    name: '1711'
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
            collapsible: true,
            collapsed: true,
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
                    name: '7877'
                }, {
                    emptyText: 'Rubro de la Empresa',
                    name: '7878'
                }]
        }, {
            xtype: 'fieldset',
            title: 'PLANTA',
            collapsible: true,
            collapsed: true,
            defaultType: 'textfield',
            layout: 'anchor',
            defaults: {
                anchor: '100%'
            },
            items: [{
                    emptyText: 'Superficie Cubierta',
                    name: '7879',
                }, {
                    emptyText: 'Posesi&oacute;n',
                    name: '7880'
                }, {
                    emptyText: 'Productos o servicios que Ofrece',
                    name: '1715',
                    xtype: 'textarea'
                }]
        }, {
            xtype: 'fieldset',
            title: 'PRODUCCION',
            collapsible: true,
            collapsed: true,
            defaultType: 'textfield',
            layout: 'anchor',
            defaults: {
                anchor: '100%'
            },
            items: [{
                    emptyText: 'Tiene componentes importados',
                    name: '7881',
                }, {
                    emptyText: 'Pueden ser reemplazados?',
                    name: '7882'
                }, {
                    emptyText: 'Tiene capacidad para exportar?',
                    name: '7883'
                }, {
                    emptyText: 'Mercado destino',
                    name: '1716'

                }, {
                    emptyText: 'Proveedores',
                    name: '7884'

                }, {
                    emptyText: 'La empresa ha realizado o realiza acciones vinculadas a la Responsabilidad Social',
                    name: '7663'

                }, {
                    emptyText: 'Registro &Uacute;nico de Organizaciones de Responsabilidad Social',
                    name: '7665'

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
                btn = Ext.getCmp('btn_save').disable();
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
            id: 'notas',
            xtype: 'textarea',
            fieldLabel: 'Notas / Observaciones',
            name: '7408',
            allowBlank: false
        },
        {
            xtype: 'combobox',
            name: 'tipovisita',
            fieldLabel: 'Tipo de Visita',
            store: TipoVisitaStore,
            queryMode: 'local',
            displayField: 'text',
            valueField: 'value',
            emptyText: 'Seleccione el Tipo de Visita',
            editable: false,
            listeners: {
                change: function(me, newValue, oldValue, eOpts) {
                    if (newValue != null) {
                        if (newValue != 5) {
                            Ext.getCmp('otros').hide();

                        } else {
                            Ext.getCmp('otros').show();
                        }

                    }
                }
            }

        }, {
            fieldLabel: 'Otros',
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
                btn = Ext.getCmp('btn_save_visita').disable();
            }
        }
    }
    , bbar: [
        btnSaveVisita
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


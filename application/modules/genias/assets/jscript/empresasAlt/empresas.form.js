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
        Ext.getCmp('btnSync').setText('Hay (' + storeEmpresaOffline.getCount() + ') para actualizar');
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
                nota: data['7408']
            });
            //--agrego al que se usa para visualizar    
            
            VisitasStore.add(visitaRecord);
            //---busco por cuit            
            //--agrego al que se usa para syncro y persistencia
            storeVisitaOffline.add(visitaRecord);
            console.log(storeVisitaOffline);
            storeVisitaOffline.sync();
            VisitasGrid.refresh();
            /*Actualizo listado de pendientes*/
            Ext.getCmp('btnSync').setText('Hay (' + storeEmpresaOffline.getCount() + ') para actualizar');
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
            fieldLabel: 'CUIT',
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
        },
        {
            fieldLabel: 'Nombre',
            name: '1693'
        },
        {
            id: 'ProvinciaCombo',
            xtype: 'combobox',
            name: '4651',
            fieldLabel: 'Provincia',
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
            fieldLabel: 'Partido',
            store: PartidoStore,
            queryMode: 'local',
            displayField: 'text',
            valueField: 'value',
            emptyText: 'Seleccione el Partido'
                    //,editable: false

        },
        {
            fieldLabel: 'Calle / Ruta',
            name: '4653'
        },
        {
            fieldLabel: 'Nro. / Km.',
            name: '4654'
        },
        {
            fieldLabel: 'Piso',
            name: '4655'
        },
        {
            fieldLabel: 'Dto / Oficina',
            name: '4656'
        },
        {
            xtype: 'hidden',
            name: '7819',
            id: 'long',
            fieldLabel: 'Longitud',
            readOnly: true
        },
        {
            xtype: 'hidden',
            name: '7820',
            id: 'lat',
            fieldLabel: 'Latitud',
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
            fieldLabel: 'TASK',
            name: 'task',
            xtype: 'hidden'
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
                //----me fijo si todavia está cargando

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
            Ext.getCmp('btnSync').setText('Hay (' + storeEmpresaOffline.getCount() + ') para actualizar..');
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
    margin: '5 5 5 25',
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
                    name: '7405',
                    fieldLabel: 'Tipo de Visita',
                    store: TipoVisitaStore,
                    queryMode: 'local',
                    displayField: 'text',
                    valueField: 'value',
                    emptyText: 'Seleccione el Tipo de Visita',
                    editable: false,
                    readOnly: true

                }
    ],
    listeners: {
        dirtychange: function(form) {
            Ext.getCmp('btnSync').setText('Hay (' + storeEmpresaOffline.getCount() + ') para actualizar..');
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
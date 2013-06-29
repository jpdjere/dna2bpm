




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
    }

});


var btnSave = Ext.create('Ext.Action', {
    id: 'btn_save',
    disabled: true,
    xtype: 'button',
    text: '<i class="icon icon-save"></i> Guardar',
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
        data = form.getValues();

        var d = new Date();
        var n = d.toISOString();
        if (data['7408']) {
            visitaRecord = Ext.create('visitaModel', {
                fecha: n,
                cuit: data['1695'],
                nota: data['7408']
            });
            //--agrego al que se usa para visualizar
            VisitasStore.add(visitaRecord);
            //--agrego al que se usa para syncro y persistencia
            storeVisitaOffline.add(visitaRecord);
            storeVisitaOffline.sync();
            VisitasGrid.refresh();
        }
    }

});

var EmpresaForm = Ext.create('Ext.form.Panel', {
    id: 'EmpresaForm',
    onCuitBlur: function() {
        console.log(this);
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
            readOnly: true
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
                blur: function(me) {
                    val = me.value
                    if (me.isValid() && me.value.length == 13) {
                        EmpresaForm.setLoading('Buscando...');
                        actualRecord = EmpresaForm.getRecord();
                        index = EmpresaStore.find('1695', val);
                        if (index >= 0) {
                            record = EmpresaStore.getAt(index);
                            if (record != actualRecord) {
                                EmpresaForm.loadRecord(record);
                            } else {
                                EmpresaForm.setLoading(false);
                            }
                        } else {
                            EmpresaForm.setLoading(false);
                        }

                    }
                }
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
                render: function() {
                    btnNew.execute();
                }
                ,
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
        /*
         {
         fieldLabel: 'Provincia',
         name: '4651',
         editable: false
         },
         {
         fieldLabel: 'Partido',
         name: '1699'
         },*/
        {
            id: 'task',
            name: 'task',
            fieldValue: 'Task',
            readOnly: true
        }, {
            id: 'notas',
            xtype: 'textarea',
            fieldLabel: 'Notas / Observaciones',
            name: '7408',
            allowBlank: false
        }
    ],
    listeners: {
        dirtychange: function(form) {
            Ext.getCmp('btnSync').setText('Hay (' + storeEmpresaOffline.getCount() + ') para actualizar..');
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



var EmpresaFormPanel = Ext.create('Ext.Panel', {
    layout: 'fit',
    items: [EmpresaForm],
});
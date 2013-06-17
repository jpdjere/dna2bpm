Ext.require([
    'Ext.data.*',
    'Ext.tip.QuickTipManager',
    'Ext.window.MessageBox'
]);

var title = (navigator.onLine) ? "Escenario Pyme MODO ON-LINE" : "Escenario Pyme MODO OFF-LINE";

/*  				 	
 C7586 	GenIA 
 C7406 	Usuario 
 C7404 	Provincia 
 C7405 	Partido 
 C7411 	Empresa visitada 
 C7407 	Fecha de la Visita 
 C7408 	Comentarios 
 C7409 	Origen 
 C7410 	Fecha de Carga  
 C7818  Task
 C7819  Longitud
 C7820  Latitud
 */





Ext.define('Writer.Form', {
    extend: 'Ext.form.Panel',
    alias: 'widget.writerform',
    requires: ['Ext.form.field.Text', 'Ext.form.ComboBox'],
    initComponent: function() {
        this.addEvents('create');
        Ext.apply(this, {
            activeRecord: null,
            iconCls: 'icon-user',
            framape: true,
            title: title,
            defaultType: 'textfield',
            bodyPadding: 15,
            fieldDefaults: {
                anchor: '100%',
                labelAlign: 'right'
            },
            items: [{
                    fieldLabel: 'ID',
                    name: 'id',
                    //allowBlank: false,
                    xtype: 'hidden',
                    readOnly: true
                }/*,
                 {
                 xtype: 'hidden',
                 name: '7586',
                 fieldLabel: 'GenIA',
                 store: GeniaStore,
                 queryMode: 'local',
                 displayField: 'text',
                 valueField: 'value',
                 emptyText: 'Seleccione la GenIA', 
                 editable: false
                 }*/,
                {
                    xtype: 'combobox',
                    name: '7404',
                    fieldLabel: 'Provincia',
                    store: ProvinciaStore,
                    queryMode: 'local',
                    displayField: 'text',
                    valueField: 'value',
                    emptyText: 'Seleccione la Provincia',
                    editable: false,
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
                    xtype: 'combobox',
                    name: '7405',
                    fieldLabel: 'Partido',
                    store: PartidoStore,
                    queryMode: 'local',
                    displayField: 'text',
                    valueField: 'value',
                    emptyText: 'Seleccione el Partido',
                    editable: false

                }, {
                    fieldLabel: 'Empresa',
                    name: '7411',
                    allowBlank: false,
                    vtype: 'CUIT', // applies custom 'IPAddress' validation rules to this field
                    emptyText: 'Ingrese un Nro de CUIT valido',
                }, {
                    xtype: 'hidden',
                    fieldLabel: 'Origen',
                    name: '7409',
                    //allowBlank: false
                    value: 'Genias 2013',
                    readOnly: true
                }, {
                    fieldLabel: 'Fecha Visita',
                    name: '7407',
                    xtype: 'datefield',
                    submitFormat: 'Y-m-d',
                    emptyText: 'Seleccione', editable: false
                }, {
                    xtype: 'textareafield',
                    name: '7408',
                    fieldLabel: 'Comentarios',
                    emptyText: 'Comentarios...'
                }, {
                    xtype: 'hidden',
                    name: '7818',
                    fieldLabel: 'Task',
                    value: this.getTask(), readOnly: true

                }, {
                    xtype: 'button',
                    fieldLabel: '',
                    text: 'Actualiza tu Geolocacion',
                    listeners: {
                        render: function() {
                            this.getEl().on('mousedown', function(e, t, eOpts) {
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
                            });
                        }
                    }

                }
                , {
                    xtype: 'hidden',
                    name: '7819',
                    id: 'long',
                    fieldLabel: 'Longitud',
                    readOnly: true
                }, {
                    xtype: 'hidden',
                    name: '7820',
                    id: 'lat',
                    fieldLabel: 'Latitud',
                    readOnly: true
                }, {xtype: 'displayfield', id: 'longLayDisplay', style: {fontSize: '11px', color: 'blue', padding: '4px'}},
            ],
            dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'bottom',
                    ui: 'footer',
                    items: [{
                            iconCls: 'icon-save',
                            itemId: 'save',
                            text: 'Actualizar',
                            disabled: true,
                            scope: this,
                            handler: this.onSave
                        }, {
                            iconCls: 'icon-user-add',
                            text: 'Agregar',
                            scope: this,
                            handler: this.onCreate
                        }, {
                            iconCls: 'icon-reset',
                            text: 'Nuevo Formulario',
                            scope: this,
                            handler: this.onReset
                        }, {
                            iconCls: 'icon-reset',
                            text: 'Volver a la Agenda',
                            scope: this,
                            handler: this.agenda
                        }]
                }]
        });
        this.callParent();

    },
    getTask: function() {
        var getParams = document.URL.split("/");
        var params = (getParams[getParams.length - 1]);
        return params;
    },
    setActiveRecord: function(record) {
        this.activeRecord = record;
        if (record) {
            this.down('#save').enable();
            this.getForm().loadRecord(record);
        } else {
            this.down('#save').disable();
            this.getForm().reset();
        }
    },
    onSave: function() {
        var active = this.activeRecord,
                form = this.getForm();
        if (!active) {
            return;
        }
        if (form.isValid()) {
            form.updateRecord(active);
            this.onReset();
        }
    },
    onCreate: function() {
        var form = this.getForm();
        if (form.isValid()) {
            this.fireEvent('create', this, form.getValues());
            form.reset();
        }

    },
    onReset: function() {
        this.setActiveRecord(null);
        this.getForm().reset();
    },
    agenda: function() {
        window.location = globals.module_url + "scheduler";

    }
});

/*
 * 
 * GRID 
 *
 *
 */

Ext.define('Writer.Grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.writergrid',
    requires: [
        'Ext.grid.plugin.CellEditing',
        'Ext.form.field.Text',
        'Ext.toolbar.TextItem'
    ],
    initComponent: function() {
        this.editing = Ext.create('Ext.grid.plugin.CellEditing');
        Ext.apply(this, {
            iconCls: 'icon-grid',
            plugins: [this.editing],
            dockedItems: [{
                    xtype: 'toolbar',
                    items: []
                }],
            columns: [/*{
             text: 'ID',
             width: 140,
             sortable: true,
             //resizable: false,
             draggable: false,
             hideable: false,
             menuDisabled: true,
             dataIndex: 'id'
             }, */
                {
                    header: 'Empresa',
                    sortable: true,
                    dataIndex: '7411'

                }, {
                    header: 'Fecha',
                    sortable: true,
                    dataIndex: '7407'
                }/*, {
                 header: 'Genia',
                 sortable: true,
                 dataIndex: '7586'
                 }*/, {
                    header: 'Comentarios',
                    flex: 1,
                    sortable: true,
                    dataIndex: '7408'
                }]
                    , dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'bottom',
                    ui: 'footer',
                    align: 'right',
                    items: [{
                            text: 'Sincronizar informaci&oacute;n',
                            scope: this,
                            handler: function() {
                                if (navigator.onLine) {
                                    Ext.getBody().mask('Sincronizando...');
                                    Ext.Ajax.request({
                                        url: globals.module_url + 'process/View',
                                        callback: function(options, success, response) {
                                            Ext.getBody().unmask();
                                            var didReset = true,
                                                    o;
                                            if (success) {
                                                try {
                                                    o = Ext.decode(response.responseText);
                                                    didReset = o.success === true;
                                                } catch (e) {
                                                    didReset = false;
                                                }
                                            }
                                            else {
                                                didReset = false;
                                            }

                                            if (didReset) {
                                                store.load();
                                            }
                                        }
                                    });

                                } else {
                                    Ext.MessageBox.alert('Error', 'Es necesario estar ONLINE para Sincronizar');
                                }
                            }
                        }]
                }]

        });


        /**/
        store.load({
            scope: this,
            callback: function(records, operation, success) {
                if (success) {
                    var getParams = document.URL.split("/");
                    var paramTask = (getParams[getParams.length - 1]);
                    var selectTaskRow = this.getSelectionModel();
                    Ext.each(records, function(record) {
                        if (record.data.task == paramTask) {
                            var row = record.index;
                            selectTaskRow.select(row, true);
                        }
                    });
                }
            }});
        /**/

        this.callParent();
        this.getSelectionModel().on('selectionchange', this.onSelectChange, this);
    },
    onSelectChange: function(selModel, selections) {
        // this.down('#delete').setDisabled(selections.length === 0);        
        var logitudData = this.getView().getSelectionModel().getSelection()[0].data[7819];
        var latitudData = this.getView().getSelectionModel().getSelection()[0].data[7819];
        var displayLongLat = (logitudData != '') ? "Longitud: " + logitudData + ' Latitud: ' + latitudData : 'NO hay informacion disponible';

        Ext.getCmp('longLayDisplay').setValue(displayLongLat);


    },
    onSync: function() {
        this.store.sync();
    },
    onDeleteClick: function() {
        var selection = this.getView().getSelectionModel().getSelection()[0];
        if (selection) {
            this.store.remove(selection);
        }
    },
    onAddClick: function() {
        var rec = new formModel({
            C7586: '', // 	GenIA  
            C7404: '', // 	Provincia 
            C7405: '', // 	Partido 
            C7411: '', // 	Empresa visitada 
            C7407: '', // 	Fecha de la Visita 
            C7408: '', // 	Comentarios 
            C7409: '', // 	Origen 
            C7818: '', // 	Task 
            C7819: '', // 	Longitud
            C7820: '', // 	Latitud

        }), edit = this.editing;
        edit.cancelEdit();
        this.store.insert(0, rec);
        edit.startEditByPosition({
            row: 0,
            column: 1
        });
    }

});


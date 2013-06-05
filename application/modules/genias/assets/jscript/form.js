Ext.require([
    'Ext.data.*',
    'Ext.tip.QuickTipManager',
    'Ext.window.MessageBox'
]);

var title = (navigator.onLine) ? "Formulario Genias ON Line Version" : "Formulario Genias OFF Line Version";

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
 task
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
                },
                {
                    xtype: 'combobox',
                    name: '7586',
                    fieldLabel: 'GenIA',
                    store: GeniaStore,
                    queryMode: 'local',
                    displayField: 'text',
                    valueField: 'value',
                    emptyText: 'Seleccione la GenIA'
                },
                {
                    xtype: 'combobox',
                    name: '7404',
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
                    xtype: 'combobox',
                    name: '7405',
                    fieldLabel: 'Partido',
                    store: PartidoStore,
                    queryMode: 'local',
                    displayField: 'text',
                    valueField: 'value',
                    emptyText: 'Seleccione el Partido'
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
                    emptyText: 'Seleccione'
                }, {
                    xtype: 'textareafield',
                    name: '7408',
                    fieldLabel: 'Comentarios',
                    emptyText: 'Comentarios...'
                }, {
                    name: 'task',
                    fieldLabel: 'Task',
                    value: this.getTask()//
                }
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
                }, {
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
                    
                    var sm = this.getSelectionModel();
                    Ext.each(records, function(record) {
                        
                        if (record.data.task==paramTask) {
                            var row = record.index;
                            sm.select(row, true);
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
        var rec = new Writer.Person({
            C7586: '', // 	GenIA 
            //C7406: '', // 	Usuario 
            C7404: '', // 	Provincia 
            C7405: '', // 	Partido 
            C7411: '', // 	Empresa visitada 
            C7407: '', // 	Fecha de la Visita 
            C7408: '', // 	Comentarios 
            C7409: '', // 	Origen 
            task: '',
            //C7410: '', // 	Fecha de Carga */

        }), edit = this.editing;
        edit.cancelEdit();
        this.store.insert(0, rec);
        edit.startEditByPosition({
            row: 0,
            column: 1
        });
    }

});


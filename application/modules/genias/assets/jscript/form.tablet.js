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
                    fieldLabel: '7406',
                    name: '7406',
                    xtype: 'hidden'
                }/*,{
                 fieldLabel: 'Usuario Tablet',
                 name: 'usuario_tablet',
                 allowBlank: false,
                 emptyText: 'User tablet',
                 }*/,
                {
                    xtype: 'combobox',
                    name: 'usuario_tablet',
                    fieldLabel: 'Usuario Tablet',
                    store: userStore,
                    queryMode: 'local',
                    displayField: 'nick',
                    valueField: 'idu',
                    emptyText: 'Seleccione el usuario'
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
                },
                {
                    fieldLabel: 'FCC',
                    name: 'fcc',
                    allowBlank: false,
                    emptyText: 'FCC ID',
                }, {
                    fieldLabel: 'Mac Address',
                    name: 'mac',
                    allowBlank: false,
                    vtype: 'MAC',
                    emptyText: 'About Tablet -> Status -> Wi-Fi Mac Ej: 5c-FF-35-7C-96-FB',
                }, {
                    xtype: 'textareafield',
                    name: '7408',
                    fieldLabel: 'Comentarios',
                    emptyText: 'Comentarios...'
                }, {
                    fieldLabel: 'Empresa',
                    name: '7411',
                    xtype: 'hidden',
                }],
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
            columns: [{
                    header: 'QR',
                    dataIndex: 'mac',
                    renderer: function(value) {
                        return Ext.String.format('<img src="qr/' + value + '" width="40%" height="40%"> ');
                    },
                    listeners: {
                        click: function(value, metaData, record, row, col, store, gridView) {
                            //IMG
                            Ext.Msg.alert('QR CODE ' + store.get('mac'), '<img src="qr/' + store.get('mac') + '">');
                        }
                    },
                },
                {
                    header: 'Usuario',
                    sortable: true,
                    dataIndex: 'usuario_tablet',
                    store: userStore,
                    queryMode: 'local',
                    displayField: 'nick',
                    valueField: 'idu',
                   renderer: function(value, metaData, record) {
                        if (value) {                            
                            var xcat = Ext.getStore('userStore');                            
                            var record = xcat.findRecord('idu', value), name = record.get('nick');
                            return name;
                        }
                    }
                }, {
                    header: 'Genia',
                    sortable: true,
                    dataIndex: '7586',
                    store: GeniaStore,
                    queryMode: 'local',
                    valueField: 'value',
                    renderer: function(value, metaData, record) {
                        if (value) {
                            var Categories = Ext.getStore('GeniaStore');
                            var record = Categories.findRecord('value', value), name = record.get('text');
                            return name;
                        }
                    }
                },
                {
                    header: 'MAC',
                    sortable: true,
                    dataIndex: 'mac'
                }, {
                    header: 'Comentarios',
                    flex: 1,
                    sortable: true,
                    dataIndex: '7408'
                }]
        });
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
            usuario_tablet: '', // 	Usuario 
            C7404: '', // 	Provincia 
            fcc: '', // 	FCC ID 
            mac: '', // 	MAC address
            C7408: '', // 	Comentarios 
            C7411: '', // 	Empresa visitada 
            C7406: '',
        }), edit = this.editing;
        edit.cancelEdit();
        this.store.insert(0, rec);
        edit.startEditByPosition({
            row: 0,
            column: 1
        });
    }
});
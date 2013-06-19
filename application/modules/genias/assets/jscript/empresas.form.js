
var EmpresaForm=Ext.create('Ext.form.Panel', {
    layout: {
            type: 'vbox',
            align: 'stretch'  // Child items are stretched to full width
        },
    margin: '5 5 5 5',
    defaultType: 'textfield',
    items: [
    {
        fieldLabel: 'Nombre',
        name: '1693'
    },
    {
        fieldLabel: 'CUIT',
        name: '1695'
    },
    {
        fieldLabel: 'Direccion',
        name: '4653'
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
                    id:'ProvinciaCombo',
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
                    id:'PartidoCombo',
                    xtype: 'combobox',
                    name: '1699',
                    fieldLabel: 'Partido',
                    store: PartidoStore,
                    queryMode: 'local',
                    displayField: 'text',
                    valueField: 'value',
                    emptyText: 'Seleccione el Partido'
                    //,editable: false

                }
    ],

    buttons: [{}]
});
var EmpresaFormPanel=Ext.create('Ext.Panel', {
    layout:'fit',
    items:[EmpresaForm]
});
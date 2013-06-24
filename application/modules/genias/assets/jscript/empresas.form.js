
var EmpresaForm=Ext.create('Ext.form.Panel', {
    id:'formEmpresa',
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
        id:'notas',
        xtype: 'textarea',
        fieldLabel:'Notas / Observaciones',
        name:'notas'
    }
    ],

    buttons: [{
        xtype: 'button',
        fieldLabel: '',
        text: '<i class="icon icon-map-marker"></i> Actualizar Geolocación',
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

    },{
        xtype:'button',
        text: '<i class="icon icon-save"></i> Guardar'
    }]
});
var EmpresaFormPanel=Ext.create('Ext.Panel', {
    layout:'fit',
    items:[EmpresaForm]
});
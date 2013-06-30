var btnSync = Ext.create('Ext.Action',
        {
            id: 'btnSync',
            text: 'Hay (' + storeEmpresaOffline.getCount() + ') para actualizar',
            iconCls: 'icon icon-cloud-upload',
            tooltip: 'Sincronizar cambios',
            handler: function() {

                var records = new Array();
                //---me guardo el proxy offline
                offlineProxy = storeEmpresaOffline.proxy;
                //---le pongo el proxy AJAX                   
                //---Marcamos Dirty cada uno de los registros
                storeEmpresaOffline.each(function(rec) {
                    rec.setDirty();
                    store.add(rec)
                });
                store.sync();
                
                
                Ext.Msg.alert('Encenario Pyme', '<h3>Actualizado con Exito</h3>');
                EmpresaForm.loadRecord(Ext.create('EmpresaModel', {}));
                storeEmpresaOffline.removeAll();
                Ext.getCmp('btnSync').setText('Hay (' + storeEmpresaOffline.getCount() + ') para actualizar');
            }
        }
);
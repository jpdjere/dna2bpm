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
                /*Datos Empresas*/
                storeEmpresaOffline.each(function(rec) {
                    rec.setDirty();
                    storeEmpresa.add(rec)
                });
                storeEmpresa.sync();

                /*Datos Visitas*/
                storeVisitaOffline.each(function(rec) {
                    rec.setDirty();
                    storeVisita.add(rec)
                });
                storeVisita.sync();
                
                var getCount = storeVisitaOffline.getCount()+storeEmpresaOffline.getCount();
                
               

                if (getCount!= 0) {
                    Ext.Msg.alert('Encenario Pyme', '<h5>Actualizado con Exito</h5>');
                } else {
                    Ext.getCmp('btnSync').setText('No Hay informacion para actualizar');
                }

                /*Borro la informacion local*/
                storeEmpresaOffline.removeAll();
                storeVisitaOffline.removeAll();
                
                /*Actualizo el contador*/  
                getCount = storeVisitaOffline.getCount()+storeEmpresaOffline.getCount();
                Ext.getCmp('btnSync').setText('Hay (' + getCount + ') para actualizar');
            }
        }
);
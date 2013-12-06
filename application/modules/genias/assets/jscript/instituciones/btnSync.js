var btnSync = Ext.create('Ext.Action',
        {
            id: 'btnSync',
            text: 'Hay (' + storeInstitucionOffline.getCount() + ') para actualizar',
            iconCls: 'icon icon-cloud-upload',
            tooltip: 'Sincronizar cambios',
            handler: function() {
                /*Check ID user*/
                checkStatusStore.load({
                    scope: this,
                    callback: function(records, operation, success) {
                        if (success) {
                            var records = new Array();
                            //---me guardo el proxy offline
                            offlineProxy = storeInstitucionOffline.proxy;
                            //---le pongo el proxy AJAX                   
                            //---Marcamos Dirty cada uno de los registros

                            /*Datos Institucions*/
                            storeInstitucionOffline.each(function(rec) {
                                rec.setDirty();
                                InstitucionStore.add(rec)
                            });
                            InstitucionStore.sync();

                            /*Datos Visitas*/
                            storeVisitaOfflineInst.each(function(rec) {
                                rec.setDirty();
                                storeVisitaInst.add(rec);
                            });
                            storeVisitaInst.sync();

                            /*REMOVE Datos Visitas*/
                            storeVisitaOfflineDeleteInst.each(function(rec) {
                                rec.setDirty();
                                storeVisitaDeleteInst.add(rec);
                            });
                            storeVisitaDeleteInst.sync();


                            /*Datos Encuestas*/
                            storeEncuestasOffline.each(function(rec) {
                                rec.setDirty();
                                storeEncuesta.add(rec)
                            });
                            storeEncuesta.sync();

                            var getCount = storeEncuestasOffline.getCount() + storeVisitaOfflineInst.getCount() + storeInstitucionOffline.getCount() + storeVisitaOfflineDeleteInst.getCount();



                            if (getCount != 0) {
                                Ext.Msg.alert('Encenario Institucional', '<h5>Actualizado con Exito</h5>');
                            } else {
                                Ext.getCmp('btnSync').setText('No Hay informacion para actualizar');
                            }

                            /*Borro la informacion local*/
                            storeInstitucionOffline.removeAll();
                            storeVisitaOfflineInst.removeAll();
                            storeEncuestasOffline.removeAll();
                            storeVisitaOfflineDeleteInst.removeAll();

                            /*Actualizo el contador*/
                            getCount = storeEncuestasOffline.getCount() + storeVisitaOfflineInst.getCount() + storeInstitucionOffline.getCount() + storeVisitaOfflineDeleteInst.getCount();
                            Ext.getCmp('btnSync').setText('Hay (' + getCount + ') para actualizar');
                        } else {
                            /*
                             * IDU check 
                             * si no tiene idu, vuelve al login
                             *
                             **/
                            Ext.getCmp('btnSync').setText('La Session Esta Vencida');
                            window.location.replace("../user/logout");                             
                            
                        }
                    }
                })
            }
        }
);
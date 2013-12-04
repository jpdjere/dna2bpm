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
                                storeInstitucion.add(rec)
                            });
                            storeInstitucion.sync();

                            /*Datos Visitas*/
                            storeVisitaOffline.each(function(rec) {
                                rec.setDirty();
                                storeVisita.add(rec);
                            });
                            storeVisita.sync();

                            /*REMOVE Datos Visitas*/
                            storeVisitaOfflineDelete.each(function(rec) {
                                rec.setDirty();
                                storeVisitaDelete.add(rec);
                            });
                            storeVisitaDelete.sync();


                            /*Datos Encuestas*/
                            storeEncuestasOffline.each(function(rec) {
                                rec.setDirty();
                                storeEncuesta.add(rec)
                            });
                            storeEncuesta.sync();

                            var getCount = storeEncuestasOffline.getCount() + storeVisitaOffline.getCount() + storeInstitucionOffline.getCount() + storeVisitaOfflineDelete.getCount();



                            if (getCount != 0) {
                                Ext.Msg.alert('Encenario Pyme', '<h5>Actualizado con Exito</h5>');
                            } else {
                                Ext.getCmp('btnSync').setText('No Hay informacion para actualizar');
                            }

                            /*Borro la informacion local*/
                            storeInstitucionOffline.removeAll();
                            storeVisitaOffline.removeAll();
                            storeEncuestasOffline.removeAll();
                            storeVisitaOfflineDelete.removeAll();

                            /*Actualizo el contador*/
                            getCount = storeEncuestasOffline.getCount() + storeVisitaOffline.getCount() + storeInstitucionOffline.getCount() + storeVisitaOfflineDelete.getCount();
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
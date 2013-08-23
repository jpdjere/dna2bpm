var notaTpl = new Ext.XTemplate(
        '<tpl for=".">',
        '<div class="img-polaroid"><fieldset><i class="icon-comments"></i> <i>{nota}"</i>',
        '<ul class="unstyled">',
        '<li><i class="icon-calendar"></i>Fecha: {fecha:date("d/m/Y")}</li>',
        /*'<li><i class="icon-remove-sign"></i> <span class="remove label label-important"> Borrar Registro </span></li>',*/
        '</ul>',
        '</fieldset></div>',
        '</tpl>'
        );

var VisitasGrid = Ext.create('Ext.view.View',
        {
            id: 'VisitasGrid',
            store: VisitasStore,            
            tpl: notaTpl,
            itemSelector: 'span.remove',
            listeners:
                    {
                        itemclick: function(me, record, item, index, e, eOpts) {
                            Ext.MessageBox.confirm('Delete', 'Esta segudo de borrar ' + record.data['fecha'], function(btn) {
                                if (btn === 'yes') {
                                    visitaRecord = Ext.create('visitaModel', {                                      
                                        fecha: record.data['fecha'], 
                                        cuit: record.data['1695'],
                                        nota: record.data['nota'],
                                        tipo: record.data['tipovisita'],
                                        otros: record.data['otros'],
                                        7898: record.data['7898']
                                    });                                   

                                    VisitasStore.remove(record);    
                                    VisitasStore.update(visitaRecord);
                                    storeVisitaDelete.add(visitaRecord); 
                                    
                                    /*Ext.Ajax.request({
                                     disableCaching: False,
                                     url: globals.module_url + 'visitas_remote/Remove/?' + record.data['_id']
                                     
                                     });*/
                                }
                            });

                        },
                    }
        });
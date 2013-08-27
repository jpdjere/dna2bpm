var notaTpl = new Ext.XTemplate(
        '<tpl for=".">',
        '<div class="img-polaroid"><fieldset><i class="icon-comments"></i># {id} <i>{nota}"</i>',
        '<ul class="unstyled">',
        '<li><i class="icon-calendar"></i>Fecha: {fecha:date("d/m/Y")}</li>',
        '<li><i class="icon-remove-sign"></i> <span class="remove label label-important"> Borrar Registro </span></li>',
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
                            Ext.MessageBox.confirm('Delete', 'Borrar ' + record.data['fecha'] + '?', function(btn) {
                                if (btn === 'yes') {
                                    visitaRecord = Ext.create('visitaModel', {
                                        id: record.data['id']
                                    });                                   

                                    VisitasStore.remove(record);    
                                    VisitasStore.update(visitaRecord);
                                    storeVisitaOfflineDelete.add(visitaRecord); 
                                    countSync();                                   
                                }
                            });

                        },
                    }
        });
var notaTpl = new Ext.XTemplate(
    '<tpl for=".">',
    '<div class="img-polaroid">',
    '<span class="fecha label label-success">{fecha}</span>',
    '<h5>{nota}</h5>',
    '</div>',
    '</tpl>'
    );
    
var VisitasGrid=Ext.create('Ext.view.View',
{
    id:'VisitasGrid',
    store:VisitasStore,    
    tpl:notaTpl,
    itemSelector: 'span.fecha',
    listeners:
        {
        itemclick:function( me, record, item, index, e, eOpts ){alert('click')}
        }
    
});
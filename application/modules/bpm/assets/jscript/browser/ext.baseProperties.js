try {
 var PropertiesSave = Ext.create('Ext.Action',
            {
                text: 'Save',
                iconCls: 'icon icon-save',
                handler: function() {
                    //var url = globals.module_url + 'kpi/save_properties/' + Ext.getCmp('propsGrid').store.data.get('idkpi').data.value;
                    var url = globals.module_url + 'kpi/save_properties/';
                    save_props(url);
                }
            });

    //---PROPERTY GRID
    function showCheck(v) {
        if (v) {
            str = "<div align=center><input type='checkbox' checked='checked' DISABLED/></div>";

        } else {
            str = "<div align=center><input type='checkbox' DISABLED/></div>";
        }
        return str;
    }
    function clickToHTML(v) {
        return Ext.util.Format.stripTags(v);

    }
    //---define custom editors for grid
    var hidden = new Ext.form.Checkbox();
    var locked = new Ext.form.Checkbox();
    var desc = Ext.create('Ext.form.TextArea', {});
    var help = Ext.create('Ext.form.TextArea', {});
    var readonly = Ext.create('Ext.form.Text', {
        readOnly: true,
        iconCls: "icon icon-lock"
    });

    var jsonEditor = Ext.create('Ext.form.Text', {
        //readOnly: true,
        iconCls: "icon icon-lock",
        listeners: {
            click: {
                element: 'el', //bind to the underlying el property on the panel
                fn: function() {
                    Ext.create('Ext.window.Window', {
                        title: 'Query Editor',
                        height: 350,
                        width: 600,
                        layout: 'fit',
                        editorId: Ext.getCmp(this.parent().id).editorId,
                        items: {// Let's put an empty grid in just to illustrate fit layout
                            xtype: 'panel',
                            border: false,
                            html: '<div id="jsoneditor"></div>' // One header just for show. There's no data,
                        },
                        listeners: {
                            show: function() {
                                var options = {
                                    mode: 'tree',
                                    modes: ['code', 'form', 'text', 'tree', 'view'], // allowed modes
                                    error: function(err) {
                                        alert(err.toString());
                                    }
                                };
                                var fval = propsGrid.store.getRec(this.editorId).data.value;
                                if (fval) {
                                    json = Ext.JSON.decode(fval);
                                } else {
                                    json = {};
                                }
                                var container = document.getElementById('jsoneditor');
                                globals.jsonEd = new JSONEditor(container, options, json);
                            }
                        },
                        close: function() {
                            propsGrid.store.setValue(this.editorId, Ext.JSON.encode(globals.jsonEd.get()));
                            this.destroy();
                        }
                    }).show();
                }
            },
            startedit: {
                element: 'el', //bind to the underlying el property on the panel
                fn: function() {

                }
            }
        }
    });


    var checkRender = function(value) {
        if (value) {
            rtn = '<div class="text-center"><img class="x-grid-checkcolumn x-grid-checkcolumn-checked" src="data:image/gif;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="></img></div>';
        } else {
            rtn = '<div class="text-center"><input type="checkbox" readonly="readonly"/></div>';
        }
        return rtn;
    }
    ///---add some flavor to propertyGrid
/*
 * name: "New Model",
documentation: "",
auditing: "",
monitoring: "",
version: "1",
author: "System Administrator",
language: "english",
namespaces: "",
targetnamespace: "http://www.omg.org/bpmn20",
expressionlanguage: "http://www.w3.org/1999/XPath",
typelanguage: "http://www.w3.org/2001/XMLSchema",
creationdate: "2014-03-17T00:00:00",
modificationdate: "2014-03-17T00:00:00"
 */
    config = {
        id: 'propsGrid',
        source: {},
        sortableColumns: true,
        disabled: true,
        sourceConfig: {
            "resourceId": {
                //editor:readonly,
                //type:'boolean'
            },
            "hidden": {
                displayName: '<i class="icon icon-eye-close"></i> Hidden',
                editor: new Ext.form.Checkbox(),
                renderer: checkRender,
                type: 'boolean'

            },
            "locked": {
                displayName: '<i class="icon icon-lock"></i> Locked',
                renderer: checkRender,
                editor: new Ext.form.Checkbox(),
                type: 'boolean'
            },
            "idwf": {
                editor: readonly
            },
            "name": {
                displayName: 'name'
            },
            'documentation': {
                displayName: 'Documentation',
                editor: clickToHTML
            },
            'monitoring': {
                displayName: 'Monitoring',
                editor: clickToHTML
            },
            'name': {
                displayName: 'name'
            },
            
        }


        ////////////////////////////////////////////////////////////////////////////
        //////////////////////   LISTENERS    /////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////

        ,
        listeners: {
            propertychange: function(source, recordId, value, oldValue, options) {
                //console.log('source',source,'recordId','recordId',this.activeRecord,value,oldValue,options);            
                var ds = mygrid.store.data.getAt(mygrid.store.data.keys.indexOf(this.activeRecord));
                //---change data on mygrid
                if (ds)
                    ds.data[recordId] = value;
                //---update cache
                pgridCache[this.activeRecord] = this.getSource();
                //---finally refresh the grid
                mygrid.getView().refresh(true);
            }
        },
        ////////////////////////////////////////////////////////////////////////////
        //////////////////////   DOCKERS    ////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////
        tbar: {
            id: 'propsGridTbar',
            items: [
                PropertiesSave
                        , {
                            xtype: 'button',
                            text: 'Refresh',
                            iconCls: 'icon icon-repeat',
                            handler: function(me) {
                                if (mygrid.selModel.getSelection()[0]) {

                                    load_props(propsGrid.url, propsGrid.idkpi, true);
                                } else {
                                    propsGrid.setSource({});
                                }

                            }
                        }
                , {
                    xtype: 'button',
                    text: 'Preview',
                    iconCls: 'icon icon-desktop',
                    handler: function(me) {
                        //load_props(propsGrid.url, propsGrid.idkpi, true);

                    }
                }
                
            ]
        }

    };
    //------------------------------------------------------------------------------
    //-------here the custom config-------------------------------------------------
    //------------------------------------------------------------------------------
    //{customProps}
    //------------------------------------------------------------------------------
    var propsGrid = Ext.create('Ext.grid.property.Grid', config);
//var propsGrid = Ext.create('Ext.ux.propertyGrid', config);

}
catch (e)
{
    txt = "There was an error on this page: ext.baseProperties.js\n\n";
    txt += e.name + "\n" + e.message + "\n\n";
    txt += "Click OK to continue.\n\n";
    alert(txt);
}

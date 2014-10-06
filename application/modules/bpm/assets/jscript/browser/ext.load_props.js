// do the Ext.Ajax.request
function load_props(url,id,nocache){
    propsGrid = Ext.getCmp('propsGrid');
    if(propsGrid){
        //---TODO check cached sources
        //---set actual id
        propsGrid.activeRecord=id;
        //console.log('serving:'+id);
        //---set actual url
        propsGrid.url=url;
        if(pgridCache[id] && !nocache){
            //---if already loaded just takeit from local cache
            propsGrid.setSource(pgridCache[id]);
        } else {       
            propsGrid.setLoading(true);
            //---select the record 
            //mygrid.selModel.select(id);
            Ext.Ajax.request({
                // the url to the remote source
                url: url,
                method: 'POST',
                // define a handler for request success
                params:{
                    idwf:globals.idwf,
                    type:mygrid.selModel.getSelection()[0].data.type
                },
                success: function(response, options){
                    var propsGrid = Ext.getCmp('propsGrid');
                    propsGrid.setSource(Ext.JSON.decode(response.responseText));
                    //---send result to the cache
                    pgridCache[id]=propsGrid.getSource();
                    propsGrid.setLoading(false);
                },
                // NO errors ! ;)
                failure: function(response,options){
                    alert('Error Loading:'+response.err);
                    propsGrid.setLoading(false);
                
                }
            });
        }
        //----enable CodeEditor btn
        Ext.getCmp('propsGrid').enable(true);
        
    }
}
function save_props(url){
    propsGrid = Ext.getCmp('propsGrid');
    if(propsGrid){
        
            //---if already loaded just takeit from local cache    
            propsGrid.setLoading(true);
            Ext.Ajax.request({
                // the url to the remote source
                url: url,
                method: 'POST',
                // define a handler for request success
                params:propsGrid.getSource(),
                success: function(response, options){
                    var propsGrid = Ext.getCmp('propsGrid');
                    //----set source with returned data
                    propsGrid.setLoading(false);
                },
                // NO errors ! ;)
                failure: function(response,options){
                    alert('Error Loading:'+response.err);
                    propsGrid.setLoading(false);
                
                }
            });
               
        
    }
}
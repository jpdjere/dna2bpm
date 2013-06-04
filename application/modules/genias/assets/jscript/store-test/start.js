 Ext.onReady(function() {
 	Ext.get('loading').remove();
 	Ext.fly('loading-mask').remove();
 	console.log("Load json");
 	provincias.load();
 });
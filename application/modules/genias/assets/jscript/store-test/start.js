 Ext.onReady(function() {
 	msg=(navigator.onLine) ? "TEST Store json ON Line Version" : "TEST Store json Genias OFF Line Version";
 	document.getElementById('loading-msg').innerHTML += '<hr/><h3>'+msg+'</h3>';
 	//Ext.get('loading').remove();
 	//Ext.fly('loading-mask').remove();
 	//console.log("Load json");
 	provincias.load();
 });
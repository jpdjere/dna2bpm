/* 
 * Function EventListener for Online/Offline Status
 * 
 */
function updateOnlineStatus(msg) {
    var status = Ext.getElementById("status");
    var condition = navigator.onLine ? "On-Line" : "Off-Line";
    var icon = navigator.onLine ? "icon-circle" : "icon-off";
    
    if(navigator.onLine){
        Ext.getCmp('btnSync').show();
    } else {
        Ext.getCmp('btnSync').hide();
    }
    
    var state = Ext.getElementById("status");
    state.innerHTML = '<i class="icon '+icon+'"></i> '+condition; 
    
}

function loaded() {
    updateOnlineStatus("load");
    document.body.addEventListener("offline", function() {
        updateOnlineStatus("offline")
    }, false);
    document.body.addEventListener("online", function() {
        updateOnlineStatus("online")
    }, false);
}



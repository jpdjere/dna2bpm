/* 
 * Function EventListener for Online/Offline Status
 * 
 */
function updateOnlineStatus(msg) {
    var status = document.getElementById("status");
    var condition = navigator.onLine ? "On-Line" : "Off-Line";
    
    if(navigator.onLine){
        Ext.getCmp('btnSync').show();
    } else {
        Ext.getCmp('btnSync').hide();
    }
    
    var state = Ext.getElementById("status");
    state.innerHTML = '<i class="icon icon-circle"></i> '+condition; 
    
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



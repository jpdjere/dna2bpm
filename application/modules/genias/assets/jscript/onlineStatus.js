/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function updateOnlineStatus(msg) {   
    var status = document.getElementById("status");
    var condition = navigator.onLine ? "ONLINE" : "OFFLINE";    
    console.log("Event: " + msg + "; status...=" + condition + "\n");
    Ext.getCmp('escenario').setTitle('Escenario Pyme' . condition);
    //status.getCmp = "Escenario Pyme" . condition;
}

function loaded() {
    updateOnlineStatus("load");
    document.body.addEventListener("offline", function() {updateOnlineStatus("offline")}, false);
    document.body.addEventListener("online", function() {updateOnlineStatus("online")}, false);
}





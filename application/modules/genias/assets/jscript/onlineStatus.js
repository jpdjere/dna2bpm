/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function updateOnlineStatus(msg) {
    var status = document.getElementById("status");
    var condition = navigator.onLine ? "ONLINE" : "OFFLINE";
    var title = navigator.onLine ? "Informaci&oacute;n del Servidor" : "Informaci&oacute;n Local";
    //status.setAttribute("class", condition);
    var state = document.getElementById("state");
    //state.innerHTML = condition;
    console.log("Event: " + msg + "; status=" + condition + "\n");
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


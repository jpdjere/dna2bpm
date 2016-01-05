/**
 * notify defaults
 */
 $.notifyDefaults({
	allow_dismiss: true,
	delay: 5000
});
//------create socketio
var socket = io.connect('http://localhost:8000');
socket.on('welcome', function(msg) {
    $.notify({message:'Registering User',delay:500});
    socket.emit('register user', globals.myidu);
});
socket.on('registered', function(msg) {
    $.notify({message:msg,delay:5000});
});

socket.on('chat message', function(msg) {
    $.notify({message:msg,delay:5000});
});
/**
 *  BPM events
 */ 

socket.on('bpm movenext_hook', function(token) {
    $.notify({message:token.title,delay:5000});
});

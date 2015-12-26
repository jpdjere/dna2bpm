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
    socket.send('register user', globals.myidu);
});

socket.on('chat message', function(msg) {
    $.notify({message:msg,delay:5000});
});

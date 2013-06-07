/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
lat: -34.924711
long: -57.946014
 */


$(document).ready(function(){
    $('#map_canvas').gmap().bind('init', function(ev, map) {
        $('#map_canvas').gmap('addMarker', {
            'position': '-34.924711,-57.946014', 
            'bounds': true
        }).click(function() {
            $('#map_canvas').gmap('openInfoWindow', {
                'content': 'Hello World!'
            }, this);
        });
    });
    
});
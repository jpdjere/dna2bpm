/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
lat: -34.924711
long: -57.946014
*/

$(document).ready(function(){
    var yourStartLatLng = new google.maps.LatLng(-34.924711, 57.946014);
    var options={
        'center':'-35.924711,-57.946014'
        ,
        'zoom':7
        ,
        //'disableDefaultUI':true, 
        'callback': function() {
            var self = this;
            $.getJSON( globals.json_url, function(data) { 
                $.each( data.markers, function(i, marker) {
                    self.addMarker({
                        'position': new google.maps.LatLng(marker.latitude, marker.longitude), 
                        'bounds':true
                    }).click(function() {
                        self.openInfoWindow({
                            'content': marker.content
                        }, this);
                    });
                });
            });
            
        }
    }
    $('#map_canvas').gmap(options);
});
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
lat: -34.924711
long: -57.946014
 */

var marker;
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
            marker = self.addMarker({
                'position': new google.maps.LatLng(-35.924711,-57.946014),
                
                'animation': google.maps.Animation.DROP
            });
        }
    }
    $('#map_canvas').gmap(options);
  
    $( $('#map_canvas').gmap('get', 'map')).click(function(e){
        //alert('lat'+e.latLng.jb+'  long:'+e.latLng.kb);
        marker[0].setPosition(new google.maps.LatLng(e.latLng.jb,e.latLng.kb));
    });
    $('#whereami').on('click',function(){
        if ("geolocation" in navigator) {
            /* geolocation is available */
            navigator.geolocation.getCurrentPosition(function(position) {
                clientPos=new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
                marker[0].setPosition(clientPos);
                $($('#map_canvas').gmap('get', 'map'))[0].setZoom(15);
                $($('#map_canvas').gmap('get', 'map'))[0].setCenter(clientPos);
                
            });
        } else {
        /* geolocation IS NOT available */
        }
        
    });
});
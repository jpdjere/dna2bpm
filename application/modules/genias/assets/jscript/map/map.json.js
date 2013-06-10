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
            if(globals.json_url){
                /*$.getJSON( globals.json_url, function(data) { 
                    $.each( data.markers, function(i, marker) {
                        self.addMarker({
                            'position': new google.maps.LatLng(marker.latitude, marker.longitude), 
                            'icon': globals.module_url+'assets/images/map/factory_marker.png',
                            'bounds':true
                        }).click(function() {
                            self.openInfoWindow({
                                'content': marker.content
                            }, this);
                        });
                    });
                });*/
            }
            
        }
    }
    $('#map_canvas').gmap(options);
    $('#mapGenias').on('click',LoadGenias);
    $('#mapDNA2').on('click',LoadDNA2);
});

var LoadGenias=function (){
    map=$( $('#map_canvas').gmap('get', 'map'));
    url=globals.module_url+'assets/json/empresasGenia.json';
    $.getJSON( globals.json_url, function(data) { 
        $.each( data.markers, function(i, marker) {
             $('#map_canvas').gmap('addMarker',{
                'position': new google.maps.LatLng(marker.latitude, marker.longitude), 
                'icon': globals.module_url+'assets/images/map/factory_marker.png',
                'bounds':true
            }).click(function() {
                 $('#map_canvas').gmap('openInfoWindow',{
                    'content': marker.content
                }, this);
            });
        });
    });
}

var LoadDNA2=function (){
    url=globals.module_url+'assets/json/empresasDNA2.json';
    $.getJSON(url, function(data) { 
        $.each( data.markers, function(i, marker) {
            $('#map_canvas').gmap('addMarker',{
                'position': new google.maps.LatLng(marker.latitude, marker.longitude), 
                'icon': globals.module_url+'assets/images/map/factory_marker_green.png',
                'bounds':true
            }).click(function() {
                 $('#map_canvas').gmap('openInfoWindow',{
                    'content': marker.content
                }, this);
            });
        });
    });
}

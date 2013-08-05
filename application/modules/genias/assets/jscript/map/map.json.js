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
    $('#map_canvas').gmap(options).bind('init',function(){
        LoadGenias();
        LoadDNA2();
    });
    $('#mapGenias').on('click',ViewGenias);
    $('#mapDNA2').on('click',ViewDNA2);
    $('#mapClear').on('click',function(){
        $('#map_canvas').gmap({
            'clear':'markers'
        });
    });
});

var ViewGenias=function(){
    $('#map_canvas').gmap('find', 'markers', {
        'property': 'tags', 
        'value': 'genia'
    }, function(marker, found) {
        marker.setVisible(found);
    });
}

var ViewDNA2=function(){
    $('#map_canvas').gmap('find', 'markers', {
        'property': 'tags', 
        'value': 'dna2'
    }, function(marker, found) {
        marker.setVisible(found);
    });
}
var LoadGenias=function (){
    map=$( $('#map_canvas').gmap('get', 'map'));
    url=globals.json_url;
    LoadJSON(url);
  
}

var LoadDNA2=function (){
    url=globals.module_url+'assets/json/empresasDNA2.json';
    LoadJSON(url);
}
var LoadJSON=function (url){
    $.getJSON(url, function(data) { 
        $.each( data.markers, function(i, marker) {
            $('#map_canvas').gmap('addMarker',{
                'position': new google.maps.LatLng(marker.latitude, marker.longitude), 
                'icon': globals.module_url+'assets/images/map/'+marker.icon,
                'bounds':true,
                'tags':marker.tags
            }).click(function() {
                $('#map_canvas').gmap('openInfoWindow',{
                    'content': marker.content
                }, this);
            });
        });
    });
}
$('input:checkbox').click(function() {
    $('#map_canvas').gmap('closeInfoWindow');
    $('#map_canvas').gmap('set', 'bounds', null);
    var filters = [];
    $('input:checkbox:checked').each(function(i, checkbox) {
        filters.push($(checkbox).val());
    });
    if ( filters.length > 0 ) {
        $('#map_canvas').gmap('find', 'markers', {
            'property': 'tags', 
            'value': filters, 
            'operator': 'OR'
        }, function(marker, found) {
            if (found) {
                $('#map_canvas').gmap('addBounds', marker.position);
            }
            marker.setVisible(found); 
        });
    } else {
        $.each($('#map_canvas').gmap('get', 'markers'), function(i, marker) {
            $('#map_canvas').gmap('addBounds', marker.position);
            marker.setVisible(true); 
        });
    }
});

$(document).ready(function() {

    AmCharts.ready(function() {
        
        
        
        var map_heat = new Array();
        $('.map_heat').each(
            function(index, item) {
                //Find the url
                var url = $(item).data("url");
                $.ajax({
                        url: url,
                        context: $(item)
                    }).done(function(data) {
                        /* Convert to am-data if needed */
                        if (typeof ammap_data_convert == 'function'){
                            data=ammap_data_convert(data);
                        }
                        /* init map */
                        map = new AmCharts.AmMap($(this).attr("id"),{
                            
                            
                            theme:"light",
                            
                        });
                        map.pathToImages = globals.base_url + "map/assets/jscript/ammap/images/";
                        map.mouseWheelZoomEnabled = true;
                       
                        map.colorSteps = 10;
                        map.theme="black";
                        var dataProvider = {
                            mapVar: AmCharts.maps.argentinaHigh,
                            areas: data.areas
                        };
                        map.areasSettings = {
                            autoZoom: true
                        };
                        map.dataProvider = dataProvider;
                       

                        var valueLegend = new AmCharts.ValueLegend();
                        valueLegend.right = 10;
                        valueLegend.minValue = "little";
                        valueLegend.maxValue = "a lot!";
                        map.valueLegend = valueLegend;
                        map.write($(this).attr("id"));
                        //map_heat[item] = map;
                        //---data contains all the parameters needed
                    })
                    .fail(function() {
                        alert("error");
                    });
            });

    }); //END AMCHARTS
    // END DOCUMENT READY
});
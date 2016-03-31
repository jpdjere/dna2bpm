var map;

AmCharts.ready(function() {
    map = new AmCharts.AmMap();
    map.pathToImages =  globals.base_url + "map/assets/jscript/ammap/images/";
    map.mouseWheelZoomEnabled = true;
    map.colorSteps = 24;

    var dataProvider = {
        mapVar: AmCharts.maps.argentinaHigh,

        areas: [{
            "id": "AR-K",
            value: 4447100
        }, {
            "id":"AR-B",
            value: 5447100
        }, {
            "id":"AR-H",
            value: 6447100
        }, {
            "id":"AR-U",
            value: 7447100
        }, {
            "id":"AR-C",
            value: 8447100
        }, {
            "id":"AR-X",
            value: 9447100
        }, {
            "id":"AR-W",
            value: 10447100
        }, {
            "id":"AR-E",
            value: 11447100
        }, {
            "id":"AR-P",
            value: 12447100
        }, {
            "id":"AR-Y",
            value: 13447100
        }, {
            "id":"AR-L",
            value: 14447100
        }, {
            "id":"AR-F",
            value: 15447100
        }, {
            "id":"AR-M",
            value: 16447100
        }, {
            "id":"AR-N",
            value: 17447100
        }, {
            "id":"AR-Q",
            value: 18447100
        }, {
            "id":"AR-R",
            value: 19447100
        }, {
            "id":"AR-A",
            value: 20447100
        }, {
            "id":"AR-Z",
            value: 21447100
        }, {
            "id":"AR-G",
            value: 22447100
        }, {
            "id":"AR-S",
            value: 23447100
        }, {
            "id":"AR-J",
            value: 24447100
        }, {
            "id":"AR-D",
            value: 25447100
        }, {
            "id":"AR-V",
            value: 26447100
        }, {
            "id":"AR-T",
            value: 27447100
        } 
            
        
        
        ]
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

    map.write("mapdiv");
});
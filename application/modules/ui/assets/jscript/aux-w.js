AmCharts.theme = AmCharts.themes.black;



AmCharts.ready(function() {
    
			var icon = "M9,0C4.029,0,0,4.029,0,9s4.029,9,9,9s9-4.029,9-9S13.971,0,9,0z M9,15.93 c-3.83,0-6.93-3.1-6.93-6.93S5.17,2.07,9,2.07s6.93,3.1,6.93,6.93S12.83,15.93,9,15.93 M12.5,9c0,1.933-1.567,3.5-3.5,3.5S5.5,10.933,5.5,9S7.067,5.5,9,5.5 S12.5,7.067,12.5,9z";
			
			var map = AmCharts.makeChart("mapdiv", {
				
		
				type: "map",
				pathToImages: globals.base_url + "map/assets/jscript/ammap/images/",
				balloon: {
					color: "#000000"
				},
				dataProvider: {
					map: "argentinaHigh",
					images: [{
						latitude: -31.866877,
						longitude: -59.062832,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "Entre R�os",
						labelShiftY: 2,
						title: "Entre Rios",
						description:"-"
						
					},{
						latitude: -26.582367,
						longitude: -54.163825,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "Misiones",
						labelShiftY: 2,
						title: "Misiones",
						description:"-"
						
						
					},{
						latitude: -24.84196,
						longitude: -60.025137,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "Formosa",
						labelShiftY: 2,
						title: "Formosa",
					    description:"-"
						
					},{
						latitude: -34.511854,
						longitude: -68.685881,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "Mendoza",
						labelShiftY: 2,
						title: "Mendoza",
						description:"-"
					},{
						latitude: -43.497523,
						longitude: -68.598399,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "Chubut",
						labelShiftY: 2,
						title: "Chubut",
						description:"-"
						
					},{
						latitude: -48.436396,
						longitude: -69.735668,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "Santa Cruz",
						labelShiftY: 2,
						title: "Santa Cruz",
						description:"-"
						
					},{
						latitude: -54.394435,
						longitude: -67.811058,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "Tierra del Fuego",
						labelShiftY: 10,
						title: "Tierra Del Fuego",
						description:"-"
						
					},{
						latitude: -38.394824,
						longitude: -69.735668,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "Neuqu�n",
						labelShiftY: 2,
						title: "Neuquen",
						description:"-"	
						
					},{
						latitude: -66.761271,
						longitude: -40.027551,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "?",
						labelShiftY: 2,
						
						title: "?",
						description: "Cantidad de Proyectos Presentados: 10 // Cantidad de Proyectos Aprobados: 10"
					},{
						latitude: -37.215457,
						longitude: -65.274073,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "La Pampa",
						labelShiftY: 2,
						title: "La Pampa",
						description:"-"	
						
					},{
						latitude: -30.967674,
						longitude: -68.685881,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "San Juan",
						labelShiftY: 2,
						title: "San Juan",
						description:"-"	
						
					},{
						latitude: -33.712165,
						longitude: -65.886449,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "San Luis",
						labelShiftY: 2,
						title: "San Luis",
						description:"-"	
						
					},{
						latitude: -31.792274,
						longitude: -63.699392,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "C�rdoba",
						labelShiftY: 2,
						title: "Córdoba",
						description:"-"	
						
					},{
						latitude: -29.450077,
						longitude: -67.023718,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "La Rioja",
						labelShiftY: 2,
						title: "La Rioja",
						description:"-"	
						
					},{
						latitude: -30.059922,
						longitude: -60.899959,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "Santa Fe",
						labelShiftY: 2,
						title: "Santa Fé",
						description:"-"	
						
					},{
						latitude: -26.974322,
						longitude: -67.198683,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "Catamarca",
						labelShiftY: 2,
						title: "Catamarca",
						description:"-"	
						
					},{
						latitude: -26.896039,
						longitude: -65.18659,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "Tucum�n",
						labelShiftY: 2,
						title: "Tucum�n",
						description:"-"	
						
					},{
						latitude: -27.520772,
						longitude: -63.349463,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "Santiago del Estero",
						labelShiftY: 2,
						title: "Santiago Del Estero",
						description:"-"	
						
					},{
						latitude: -26.503814,
						longitude: -60.55003,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "Chaco",
						labelShiftY: 2,
						title: "Chaco",
						description:"-"	
					
					},{
						latitude: -22.834168,
						longitude: -65.798966,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "Jujuy",
						labelShiftY: 2,
						title: "Jujuy",
						description: "-"
						
					},{
						latitude: -36.724379,
						longitude: -60.112619,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "Buenos Aires",
						labelShiftY: 2,
						title: "Buenos Aires",
						description: "Provincia de Buenos Aires"
						
					},
                    {
						latitude: -25.160281,
						longitude: -64.574215,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "Salta",
						labelShiftY: 2,
						title: "Salta",
						description:"-"	
						
					},{
						latitude: -29.067049,
						longitude: -57.575633,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "Corrientes",
						labelShiftY: 2,
						title: "Corrientes",
						description:"-"
						
					},{
						latitude: -40.027551,
						longitude: -66.761271,
						svgPath: icon,
						color: "#CC0000",
						scale: 0.5,
						// label: "R�o Negro",
						labelShiftY: 2,
						title: "Rio Negro",
						description:"-"
						
					}]
				},
				developerMode: true
			});
			












    map.addListener("clickMapObject", function (event) {
    	
    	 $.ajax({
            type: "POST",
            context: $('#table'),
            url: globals.base_url + 'pacc/incubar/reload_table/' + (event.mapObject.index)+'/'+ (event.mapObject.title),
            success: function(data) {                
                     $(this).replaceWith(data);            
                     }});
                     

     if ((event.mapObject.index) == 20){
     	 $.ajax({
             type: "POST",
             async: false,
             context: $('#mapdiv'),
             url: globals.base_url + 'pacc/incubar/reload_map',
             success: function(data) {  
                      $(this).replaceWith(data);  
                      partidos_init();
                      }});
        
        
        $.ajax({
             type: "POST",
             async: false,
             context: $('#table'),
             url: globals.base_url + 'pacc/incubars/reload_table/BUE/null',
             success: function(data) {  
                      $(this).replaceWith(data);  
                      }});
     }
    });
});  


	
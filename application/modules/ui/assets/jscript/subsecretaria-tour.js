$(document).ready(function() {

var tour = new Tour({
		storage : false
	});



(function(){
	var name = "Friend";

	tour.addSteps([
	  {
	    element: ".tour-subsecretaria.paso-uno",
	    placement: "top",
	    backdrop: true,
	    title: "Mapa de Incubadoras",
	    content: "Muestra la cantidad de Incubadoras que hay en cada provincia, en el caso de la provincia de Buenos Aires abre un informe más detallado",
	    // onNext : function(tour){
	    // 	var nameProvided = $("input[name=your_name]").val();
	    // 	if ($.trim(nameProvided) !== ""){
	    // 		name = nameProvided;
	    // 	}
	    // }
	  },
	  {
	    element: ".tour-subsecretaria.paso-dos",
	    placement: "top",
	    backdrop: true,
	    title: "Proyectos Empresas", //function(){ return "Welcome, " + name; },
	    content: "Muestra el desempeño de las incubadoras agrupado por provincia."
	  },
	  {
	    element: ".tour-subsecretaria.paso-tres",
	    placement: "top",
	    backdrop: true,
	    title: "Proyectos Emprendedores",
	    content: "Muestra el desempeño de las incubadoras agrupado por provincia."
	  },
	  {
	    element: ".tour-subsecretaria.paso-cuatro",
	    placement: "top",
	    backdrop: true,
	    orphan: true,
	    title: "Incubadoras según Provincia y Localidad",
	    content: "Muestra el estado de las metas para emprendedores." // function(){ return "We can't wait to see what you think, "+name+"!" }
	  },

	]);

	// Initialize the tour
	tour.init();

	// Start the tour
   //	tour.start();

}());




      $('#button').click(function() {
  
       tour.start();
      });


});
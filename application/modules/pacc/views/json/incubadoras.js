$(document).ready(function() {

var tour = new Tour({
		storage : false
	});



(function(){
	var name = "Friend";

	tour.addSteps([
	  {
	    element: ".tour-incubadoras.incubadoras-uno",
	    placement: "top",
	    backdrop: true,
	    title: "Mapa Incubar",
	    content: "Muestra los proyectos que presentan más atraso respecto a su planificación.",
	    // onNext : function(tour){
	    // 	var nameProvided = $("input[name=your_name]").val();
	    // 	if ($.trim(nameProvided) !== ""){
	    // 		name = nameProvided;
	    // 	}
	    // }
	  },
	  {
	    element: ".tour-incubadoras.incubadoras-dos",
	    placement: "top",
	    backdrop: true,
	    title: "Proyectos Empresas ", //function(){ return "Welcome, " + name; },
	    content: "Muestra el desempeño mensual de cada analista."
	  },
	  {
	    element: ".tour-incubadoras.incubadoras-tres",
	    placement: "top",
	    backdrop: true,
	    title: "Proyectos Emprendedores",
	    content: "Muestra las solicitudes de desembolso."
	  },
	  {
	    element: ".tour-incubar.incubar-cuatro",
	    placement: "top",
	    backdrop: true,
	    orphan: true,
	    title: "Ranking de Incubadoras",
	    content: "Muestra las solicitudes de desembolso."
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
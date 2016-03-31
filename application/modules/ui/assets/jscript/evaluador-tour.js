$(document).ready(function() {

var tour = new Tour({
		storage : false
	});



(function(){
	var name = "Friend";

	tour.addSteps([
	  {
	    element: ".tour-evaluador.evaluador-uno",
	    placement: "top",
	    backdrop: true,
	    title: "Listado de Proyectos",
	    content: "Muestra los proyectos que presentan más atraso respecto a su planificación.",
	    // onNext : function(tour){
	    // 	var nameProvided = $("input[name=your_name]").val();
	    // 	if ($.trim(nameProvided) !== ""){
	    // 		name = nameProvided;
	    // 	}
	    // }
	  },
	  {
	    element: ".tour-evaluador.evaluador-dos",
	    placement: "top",
	    backdrop: true,
	    title: "Desempeño de analistas", //function(){ return "Welcome, " + name; },
	    content: "Muestra el desempeño mensual de cada analista."
	  },
	  {
	    element: ".tour-evaluador.evaluador-tres",
	    placement: "top",
	    backdrop: true,
	    orphan: true,
	    title: "Task Pending",
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
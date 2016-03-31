$(document).ready(function() {

var tour = new Tour({
		storage : false
	});



(function(){
	var name = "Friend";

	tour.addSteps([
	  {
	    element: ".tour-step.tour-step-one",
	    placement: "top",
	    backdrop: true,
	    title: "Prioridades",
	    content: "Muestra los proyectos que presentan más atraso respecto a su planificación.",
	    // onNext : function(tour){
	    // 	var nameProvided = $("input[name=your_name]").val();
	    // 	if ($.trim(nameProvided) !== ""){
	    // 		name = nameProvided;
	    // 	}
	    // }
	  },
	  {
	    element: ".tour-step.tour-step-two",
	    placement: "top",
	    backdrop: true,
	    title: "Desempeño Analistas", //function(){ return "Welcome, " + name; },
	    content: "Muestra el desempeño mensual de cada analista."
	  },
	  {
	    element: ".tour-step.tour-step-three",
	    placement: "top",
	    backdrop: true,
	    title: "SDE",
	    content: "Muestra las solicitudes de desembolso."
	  },
	  {
	    element: ".tour-step.tour-step-four",
	    placement: "top",
	    backdrop: true,
	    orphan: true,
	    title: "Metas para emprendedores",
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
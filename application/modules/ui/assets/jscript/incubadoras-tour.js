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
	    title: "Estado de los Proyectos Presentados",
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
	    orphan: true,
	    title: "Ranking de Incubadoras",
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
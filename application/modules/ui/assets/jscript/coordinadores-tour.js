$(document).ready(function() {

var tour = new Tour({
		storage : false
	});



(function(){
	var name = "Friend";

	tour.addSteps([
	  {
	    element: ".tour-coordinadores.paso-uno",
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
	    element: ".tour-coordinadores.paso-dos",
	    placement: "top",
	    backdrop: true,
	    title: "Desempeño de analistas", //function(){ return "Welcome, " + name; },
	    content: "Muestra el desempeño mensual de cada analista."
	  },
	  {
	    element: ".tour-coordinadores.paso-tres",
	    placement: "top",
	    backdrop: true,
	    title: "Proyectos Reclamados",
	    content: "Muestra las solicitudes de desembolso."
	  },
	  {
	    element: ".tour-coordinadores.paso-cuatro",
	    placement: "top",
	    backdrop: true,
	    title: "Metas para Empresas",
	    content: "Muestra las solicitudes de desembolso."
	  },
	  {
	    element: ".tour-coordinadores.paso-cinco",
	    placement: "top",
	    backdrop: true,
	    title: "SDE - Últimos 28 días",
	    content: "Muestra los últimos 28 días ingresados y evaluados."
	  },
	  {
	    element: ".tour-coordinadores.paso-seis",
	    placement: "top",
	    backdrop: true,
	    title: "PITCHs",
	    content: "Muestra el estado de los PITC presentados"
	  },
	  {
	    element: ".tour-coordinadores.paso-siete",
	    placement: "top",
	    backdrop: true,
	    title: "Mapa de Proyectos Presentados",
	    content: "Muestra en el mapa las solicitudes"
	  },
	  {
	    element: ".tour-coordinadores.paso-ocho",
	    placement: "top",
	    backdrop: true,
	    orphan: true,
	    title: "Metas para Emprendedores",
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
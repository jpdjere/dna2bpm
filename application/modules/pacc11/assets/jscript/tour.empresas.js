$(document).ready(function() {

var tour = new Tour({
		storage : false
	});



(function(){
	var name = "Friend";

	tour.addSteps([
	  {
	    element: "#tile_solicitud_PACC11",
	    placement: "right",
	    backdrop: true,
	    title: "Iniciar un Proyecto",
	    content: "Hagá click en este cuadro para comenzar un nuevo Proyecto",
	    // onNext : function(tour){
	    // 	var nameProvided = $("input[name=your_name]").val();
	    // 	if ($.trim(nameProvided) !== ""){
	    // 		name = nameProvided;
	    // 	}
	    // }
	  },
	  {
	    element: "#pacc11_PP",
	    placement: "right",
	    backdrop: true,
	    title: "Proyectos Empresas", //function(){ return "Welcome, " + name; },
	    content: "Muestra la cantidad de Proyectos Presentados y permite obtener un listado haciendo click"
	  },
	  {
	    element: "#pacc11_PPA",
	    placement: "left",
	    backdrop: true,
	    title: "Proyectos Preaprobados",
	    content: "Aquí se mostrará la cantidad de Proyectos que han sido prepaprobados, también recibirá una notificación via correo electrónico, permite obtener un listado haciendo click"
	  },
	  {
	    element: "#pacc11_PA",
	    placement: "left",
	    backdrop: true,
	    orphan: true,
	    title: "Proyectos Aprobados",
	    content: "Muestra la cantidad de proyectos Aprobados y permite obtener un listado haciendo click" // function(){ return "We can't wait to see what you think, "+name+"!" }
	  },
	  {
	    element: ".box-warning",
	    placement: "right",
	    backdrop: true,
	    orphan: true,
	    title: "Listado de Tareas",
	    content: "Se muestran aquí las tareas que tiene pendiente respecto de los proyectos presentados" // function(){ return "We can't wait to see what you think, "+name+"!" }
	  },

	]);

	// Initialize the tour
	tour.init();

	// Start the tour
   //	tour.start();

}());




      $('#button-tour').click(function() {
       tour.start();
      });


});
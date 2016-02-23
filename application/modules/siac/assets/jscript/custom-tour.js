$(document).ready(function() {

var tour = new Tour({
		storage : false
	});


(function(){
	var name = "Friend";

	tour.addSteps([
	  {
	    element: ".tour-incubar.incubar-uno",
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
	    element: ".tour-incubar.incubar-dos",
	    placement: "top",
	    backdrop: true,
	    title: "Proyectos Empresas ", //function(){ return "Welcome, " + name; },
	    content: "Muestra el desempeño mensual de cada analista."
	  },
	  {
	    element: ".tour-incubar.incubar-tres",
	    placement: "top",
	    backdrop: true,
	    title: "Proyectos Emprendedores",
	    content: "Muestra las solicitudes de desembolso."
	  },
	  {
	    element: ".tour-incubar.incubar-cuatro",
	    placement: "top",
	    backdrop: true,
	    title: "Incubadoras según Provincia y Localidad",
	    content: "Muestra el estado de las metas para emprendedores." // function(){ return "We can't wait to see what you think, "+name+"!" }
	  },
	  {
	    element: ".tour-incubar.incubar-cinco",
	    placement: "top",
	    backdrop: true,
	    title: "Estado de las Incubadoras",
	    content: "Muestra el estado de las metas para emprendedores." // function(){ return "We can't wait to see what you think, "+name+"!" }
	  },
	  {
	    element: ".tour-incubar.incubar-seis",
	    placement: "top",
	    backdrop: true,
	    title: "Deuda Total / Retribución pagada",
	    content: "Muestra el estado de las metas para emprendedores." // function(){ return "We can't wait to see what you think, "+name+"!" }
	  },
	  {
	    element: ".tour-incubar.incubar-siete",
	    placement: "top",
	    backdrop: true,
	    title: "Deuda Total / Retribución no pagada",
	    content: "Muestra el estado de las metas para emprendedores." // function(){ return "We can't wait to see what you think, "+name+"!" }
	  },
	  {
	    element: ".tour-incubar.incubar-ocho",
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
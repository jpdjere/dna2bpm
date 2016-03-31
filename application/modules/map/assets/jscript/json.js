    $(document).ready(function() {

   
    $('.map_table').each(
     function(index, item) {
      //Find the url
      var url = $(item).find(".json_url").text();
      $.ajax({
       url: url,
       context: $(item)
      }).done(function(data) {
        $(data).each(
         function(index, provincia) {

          var table = $(item).find('table');
          var tr = $('#table').clone();

          $(tr).find('.pad-seccion').text(data.seccion);
          $(tr).find('.pad-cantidad').text(data.cantidad);
          
          $(tr).removeClass('hidden');
          $(table).append(tr);
         }
        );
       }
       //---data contains all the parameters needed
      );
      //para mi la estructura de la vista no es valida.. es un array de parser.. se puede eso?
     });
     
     
     
     
     
     
    });
   $(document).ready(function() {

       $('.team_ranking_json').each(
           function(index, item) {
               //Find the url
               var url = $(item).find(".json_url").text();
               $.ajax({
                   url: url,
                   context: $(item)
               }).done(function(data) {
                       $(data).each(
                           function(index, user) {

                               var table = $(item).find('table');
                               var tr = $('#tr_ranking').clone();

                               $(tr).find('.pad-nombre').text(user.name);
                               $(tr).find('.pad-progress .progress div').addClass('progress-bar-' + user.class);
                               $(tr).find('.badge').text(user.value);
                               $(tr).find('.pad-nro').find('span').addClass('badge bg-' + user.color);
                               $(tr).find('.progress div').css('width', user.value + '%');
                               $(tr).attr('id', user.id);
                               $(tr).find('img').attr('src', user.avatar);
                               $(tr).removeClass('hidden');
                               $(table).append(tr);
                           }
                       );
                   }
                   //---data contains all the parameters needed
               );
               //para mi la estructura de la vista no es valida.. es un array de parser.. se puede eso?
           });



       //----end document ready
   });
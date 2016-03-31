 
    $(document).ready(function() {
   
     $('.json_tl').each(
         function(index, item) {
             //Find the url
             var url = $(item).find(".json_url").text();
             $.ajax({
                 url: url,
                 context: $(item)
             }).done(function(data) {
                $(item).find('.title').text(data.title);
                $(item).find('.icon').text(data.icon);
                $(item).find('.background').text(data.background);
                $(item).find('.content').text(data.content);
                 //---data contains all the parameters needed
             });
         });

});
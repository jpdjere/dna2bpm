 $(document).ready(function() {

     var semaphore = new Array();
     $('.semaphore').each(
         function(index, item) {
             //Find the url
             var url = $(item).find(".json_url").text();
             $.ajax({
                 url: url,
                 context: $(item)
             }).done(function(data) {
                 defaults = {
                     id: $(this).attr('id'),
                     gaugeWidthScale: 0.7,
                 }
                 semaphore[item] = new JustGage($.extend(defaults, data));
                 //---data contains all the parameters needed
             });
         });

     var success = new Array();
     $('.success').each(
         function(index, item) {
             //Find the url
             var url = $(item).find(".json_url").text();
             $.ajax({
                 url: url,
                 context: $(item)
             }).done(function(data) {
                 defaults = {
                     id: $(this).attr('id'),
                     gaugeWidthScale: 0.7,
                     levelColors :['#5cb85c']
                 }
                 success[item] = new JustGage($.extend(defaults, data));
                 //---data contains all the parameters needed
             });
         });
     var warning = new Array();
     $('.warning').each(
         function(index, item) {
             //Find the url
             var url = $(item).find(".json_url").text();
             $.ajax({
                 url: url,
                 context: $(item)
             }).done(function(data) {
                 defaults = {
                     id: $(this).attr('id'),
                     gaugeWidthScale: 0.7,
                     levelColors :["#f0ad4e"]

                 }
                 warning[item] = new JustGage($.extend(defaults, data));
                 //---data contains all the parameters needed
             });
         });
     var danger = new Array();
     $('.danger').each(
         function(index, item) {
             //Find the url
             var url = $(item).find(".json_url").text();
             $.ajax({
                 url: url,
                 context: $(item)
             }).done(function(data) {
                 defaults = {
                     id: $(this).attr('id'),
                     gaugeWidthScale: 0.7,
                     levelColors : ['#d9534f']
                 }
                 danger[item] = new JustGage($.extend(defaults, data));
                 //---data contains all the parameters needed
             });
         });
     var info = new Array();
     $('.info').each(
         function(index, item) {
             //Find the url
             var url = $(item).find(".json_url").text();
             $.ajax({
                 url: url,
                 context: $(item)
             }).done(function(data) {
                 defaults = {
                     id: $(this).attr('id'),
                     gaugeWidthScale: 0.7,
                     levelColors : ['#337ab7']
                 }
                 info[item] = new JustGage($.extend(defaults, data));
                 //---data contains all the parameters needed
             });
         });


     $('.json_knob').each(
         function(index, item) {
             //Find the url
             var url = $(item).data("url");
             $.ajax({
                 url: url,
                 context: $(item)
             }).done(function(data) {
                $(item).val(data.value);
                $(item).data("min", data.min);
                $(item).data("max", data.max);
                $(item).data("label", data.label);
                $($(item).parent().parent().find(".knob-label b")).html(data.label);
                $(item).data("title", data.title);
                $(item).trigger("change");
                 //---data contains all the parameters needed
             });
         });

     $('.json_knob_consolidado').each(
         function(index, item) {
             //Find the url
             var url = $(item).data("url");
             $.ajax({
                 url: url,
                 context: $(item)
             }).done(function(data) {
                $(item).val(data.value);
                $(item).data("min", data.min);
                $(item).data("max", data.max);
                $(item).data("label", data.label);
                $(item).data("title", data.title);
                $(item).trigger("change");
                 //---data contains all the parameters needed
             });
         });

     //----end document ready
 });
   $(document).ready(function() {
        
   $(function () {
        "use strict";

      
      $.ajax({
       url: $('#sde_charts').find(".json_url").text(),
       context: $('#sde_charts')
         }).done(function(data) {
          
      var area = new Morris.Line({
          element: 'sde_charts',
          resize: true,
          data: data,
          xkey: 'y',
          ykeys: ['item1', 'item2'],
          labels: ['Ingresada', 'Evaluada'],
          lineColors: ['#a0d0e0', '#3c8dbc'],
          hideHover: 'auto'
        });
         
       
      });
        
     
        
   });     
    //----end document ready
   });
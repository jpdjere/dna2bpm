   $(document).ready(function() {
        
   $(function () {
        "use strict";
      $.ajax({
       url: $('#chart-pagadas').find(".json_url").text(),
       context: $('#chart-pagadas')
         }).done(function(data) {
      var area = new Morris.Line({
          element: 'chart-pagadas',
          resize: true,
          data: data,
          xkey: 'y',
          ykeys: ['item1', 'item2'],
          labels: ['Retribuciones Pagadas', 'Deuda Total'],
          lineColors: ['#a0d0e0', '#3c8dbc'],
          hideHover: 'auto'
        });
         
       
      });
          
          
      $.ajax({
       url: $('#chart-no-pagadas').find(".json_url").text(),
       context: $('#chart-no-pagadas')
         }).done(function(data) {
          
      var area = new Morris.Line({
          element: 'chart-no-pagadas',
          resize: true,
          data: data,
          xkey: 'y',
          ykeys: ['item1', 'item2'],
          labels: ['Retribuciones No Pagadas', 'Deuda Total'],
          lineColors: ['#a0d0e0', '#3c8dbc'],
          hideHover: 'auto'
        });
         
       
      });
      

        
     
        
   });     
    //----end document ready
   });
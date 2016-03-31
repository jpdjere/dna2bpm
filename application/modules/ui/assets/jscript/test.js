 
$(document).ready(function() {
   
   
     $(document).on('click', '#graph', function(e) {
       downloadCanvas(this, '#test', 'test.png');
     });
    
    
    function downloadCanvas(link, canvasId, filename) {
    link.download = filename;
    link.href = $('#test').toDataURL("image/png");
    }

});

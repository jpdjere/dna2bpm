//---morris indexer
var morris = 1;
$(document).ready(function() {
    $('.morris_line').each(function(obj) {
        var id = 'morris_line' + morris++
            $(this).attr('id', id);
        $.ajax({
            url: $(this).parents().find(".json_url").text(),
            context: $(this)
        }).done(function(data) {
            new Morris.Line({
                element: id,
                resize: true,
                data: data.data,
                xkey: data.key,
                ykeys: data.items,
                labels: data.labels,
                postUnits:data.postUnits,
                lineColors: ['#a0d0e0', '#3c8dbc'],
                hideHover: 'auto'
            });


        });
    });
    //----end document ready
});
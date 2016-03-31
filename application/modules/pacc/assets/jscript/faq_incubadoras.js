$(document).ready(function() {
    $('.collapse').on('show.bs.collapse', function() {
        var id = $(this).attr('id');
        $('a[href="#' + id + '"]').closest('.panel-heading').addClass('active-faq');
        $('a[href="#' + id + '"] .panel-title span').html('<i class="glyphicon glyphicon-minus"></i>');
    });
     $('.panel-faq').on('show.bs.collapse', function () {
         $(this).addClass('active');
    });

    $('.panel-faq').on('hide.bs.collapse', function () {
         $(this).removeClass('active');
    });
    
    
});


   
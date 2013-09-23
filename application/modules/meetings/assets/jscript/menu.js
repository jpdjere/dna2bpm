$(document).ready(function() {
});

    $(document).on("click", "a", function(e) {

        isMenu = $(this).attr('isMenu');
        isClear = $(this).hasClass('ui-input-clear')
        //---if has isMenu then load whole page
        if (!(isMenu || isClear)) {

            e.preventDefault();

            //$.mobile.showPageLoadingMsg('a', 'loading...', true);
            $('.content-primary').load($(this).attr('href'), function() {
                $.mobile.hidePageLoadingMsg();
                $('.content-primary').trigger("create");
            });
            return false;
        }
    });

    $('form').on('submit', function(e) {
        e.preventDefault();
        $.post($(this).attr("action"), $(this).serialize(), function(html) {
            $('.content-primary').html(html);
            $('.content-primary').trigger("create");
        });
        return false; // prevent normal submit
    });
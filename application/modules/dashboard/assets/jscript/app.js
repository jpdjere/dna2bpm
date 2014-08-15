/**
 * Main JS
 * Author: Gabriel Fojo
 **/

$(document).ready(function() {
    (function($) {
        "use strict";

        $.fn.tree = function() {

            return this.each(function() {
                var btn = $(this).children("a").first();
                var menu = $(this).children(".treeview-menu").first();
                var isActive = $(this).hasClass('active');

                //initialize already active menus
                if (isActive) {
                    menu.show();
                    btn.children(".fa-angle-left").first().removeClass("fa-angle-left").addClass("fa-angle-down");
                }
                //Slide open or close the menu on link click
                btn.click(function(e) {
                    e.preventDefault();
                    if (isActive) {
                        //Slide up to close menu
                        menu.slideUp();
                        isActive = false;
                        btn.children(".fa-angle-down").first().removeClass("fa-angle-down").addClass("fa-angle-left");
                        btn.parent("li").removeClass("active");
                    } else {
                        //Slide down to open menu
                        menu.slideDown();
                        isActive = true;
                        btn.children(".fa-angle-left").first().removeClass("fa-angle-left").addClass("fa-angle-down");
                        btn.parent("li").addClass("active");
                    }
                });

                /* Add margins to submenu elements to give it a tree look */
                menu.find("li > a").each(function() {
                    var pad = parseInt($(this).css("margin-left")) + 10;

                    $(this).css({"margin-left": pad + "px"});
                });

            });

        };


    }(jQuery));
    /* Sidebar tree view */
    $(".sidebar .treeview").tree();

    $('.form-extra').ajaxForm({
        target: '#tiles_after section',
        replaceTarget: false
    });
    // ==== Reload Widget
    $(document).on('click', '.reload_widget', function(event) {
        event.preventDefault();
        var box = $(this).parents('.box');
        var url = $(this).attr('href');

        $.ajax({
            url: url,
            context: box
        }).done(function(data) {
            $(this).replaceWith(data);
        });
    });

    // ==== Load Tiles 
    $(document).on('click', '.load_tiles_after', function(event) {
        event.preventDefault();
        var box = $(this).parents('.box');
        var url = $(this).attr('href');

        $.ajax({
            url: url,
            context: box
        })
                .done(function(data) {
                    $('#tiles_after section').html(data);
                })
                .error(function(jqXHR, textStatus, errorThrown) {
                    $('#tiles_after section').html(textStatus + errorThrown);
                })
                ;
    });

// ==== Load Modal 
    $(document).on('click', '.load_modal', function(event) {
        event.preventDefault();

        var url = $(this).attr('href');
        var title = $(this).attr('title') ? ($(this).attr('title')) : ('Title');
        $.ajax({
            url: url,
            context: document.body
        })
                .done(function(data) {
                    $('#myModal').find('.modal-title').html(title);
                    $('#myModal').find('.modal-body').html(data);
                    $('#myModal').modal('show');

                })
                .error(function(jqXHR, textStatus, errorThrown) {
                    $('#tiles_after section').html(textStatus + errorThrown);
                })
                ;
    });

// ==== Make the dashboard widgets sortable Using jquery UI
    $(".connectedSortable").sortable({
        placeholder: "sort-highlight",
        connectWith: ".connectedSortable",
        handle: ".box-header, .nav-tabs",
        forcePlaceholderSize: true,
        zIndex: 999999
    }).disableSelection();
    $(".box-header, .nav-tabs").css("cursor", "move");
    //jQuery UI sortable for the todo list
    $(".todo-list").sortable({
        placeholder: "sort-highlight",
        handle: ".handle",
        forcePlaceholderSize: true,
        zIndex: 999999
    }).disableSelection();
    ;

    //=========== ICHECK 

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"]').iCheck({
        checkboxClass: 'icheckbox_minimal',
        radioClass: 'iradio_minimal'
    });

    //When unchecking the checkbox
    $(document).on('ifUnchecked', "#check-all", function(event) {
        //Uncheck all checkboxes
        $("input[type='checkbox']", ".table-mailbox").iCheck("uncheck");
    });

    //When checking the checkbox
    $("#check-all").on('ifChecked', function(event) {
        $("input[type='checkbox']", ".table-mailbox").iCheck("check");
    });



});
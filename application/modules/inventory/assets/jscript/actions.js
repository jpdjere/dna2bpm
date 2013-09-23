$(document).on('click', '#btn_seach', function() {
    type = $("#type option:selected").val();
    code = $("#code").val();
    $('#result').load();
    $.ajax({
        'url': globals.module_url + 'info',
        'type': 'POST',
        'data': {
            'type': type,
            'code': code
        },
        'success': function(result) {
            $('#result').html(result);
        }
    });
});

$(document).on('click', '#btn_gencode', function() {
    type = $("#type option:selected").val();
    code = $("#code").val();
    $('#result').load();
    $.ajax({
        'url': globals.module_url + 'gencode',
        'type': 'POST',
        'data': {
            'type': type,
            'code': code
        },
        'success': function(result) {
            $('#result').html(result);
        }
    });
});

$(document).on('click', '#btn_showobjects', function() {
    idu = $("#user option:selected").val();
    $.ajax({
        'url': globals.module_url + 'show_objects/' + idu,
        'type': 'POST',
        'success': function(result) {
            $('#result').html(result);
        }
    });
});
$(document).on('change', '#group_select', function() {
    //console.log($(this),$(this).val());
    idgroup = $('#group_select option:selected').val();
    $.post(globals.module_url + "get_users/" + idgroup,
            function(data) {
                var sel = $("#user_select");
                sel.empty();
                for (var i = 0; i < data.length; i++) {
                    sel.append('<option value="' + data[i].idu + '">' + data[i].name + ' ' + data[i].lastname + '</option>');
                }
            }, "json");
});

$(document).on('click', '#btn_claim', function() {
    type = $("#type option:selected").val();
    code = $("#code").val();
    bootbox.dialog("Desea Reclamar la Carpeta: " + type + ' ' + code + ' ?', [{
            "label": '<i class="icon-ok-sign"></i> Si',
            "class": "btn-success",
            "callback": function() {

                type = $("#type option:selected").val();
                code = $("#code").val();
                $('#result').load();
                $.ajax({
                    'url': globals.module_url + 'claim',
                    'type': 'POST',
                    'data': {
                        'type': type,
                        'code': code
                    },
                    'success': function(result) {
                        $('#result').html(result);
                    }
                });
            }
        },
        {
            "label": '<i class="icon-remove-sign"></i> No',
            "class": "btn-danger",
        }
    ]
            );
})
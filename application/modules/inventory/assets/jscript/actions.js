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

$(document).on('click', '#btn_claim', function() {
    type = $("#type option:selected").val();
    code = $("#code").val();
    bootbox.dialog("Desea Reclamar la Carpeta: " + type + ' ' + code + ' ?', [{
            "label": "Si",
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
            "label": "No",
            "class": "btn-danger",
        }
    ]
            );
})
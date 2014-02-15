/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    $('[id^="others_"]').hide();
    /*RECTIFICAR*/
    $('[id^="rectify_"]').change(function() {
        var option_value = $(this).val();
        if (option_value == 3) {
            $('[id^="others_"]').show();
        } else {
            $('[id^="others_"]').hide();
        }
    });
    
    $("#error").html('<i class="fa fa-info-circle"></i> Si rectifica, la información asociada y relacionada será borrada del sistema');
    $("#is_session").show();
    $("#no_session").hide();
    
});

        
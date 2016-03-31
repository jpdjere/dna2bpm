    $(document).ready(function () {

    var navListItems = $('div.setup-panel div a'),
            allWells = $('.setup-content'),
            allNextBtn = $('.nextBtn');

    allWells.hide();

    navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
                $item = $(this);

        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-primary').addClass('btn-default');
            $item.addClass('btn-primary');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });

    allNextBtn.click(function(){
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url']"),
            isValid = true;

        $(".form-group").removeClass("has-error");
        for(var i=0; i<curInputs.length; i++){
            if (!curInputs[i].validity.valid){
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }

        if (isValid)
            nextStepWizard.removeAttr('disabled').trigger('click');
    });

    $('div.setup-panel div a.btn-primary').trigger('click');
    
   $(document).on('change', '#Area', function(e)  {
        $.ajax({
            type: "POST",
            url: globals.base_url + 'pacc/poa/reload_componentes/' + $("#Area").val(),
            success: function(data) {
                $('#Comp').replaceWith(data);
            }
       });
    });
    
    $(document).on('change', '#Comp', function(e)  {
        $.ajax({
            type: "POST",
            url: globals.base_url + 'pacc/poa/reload_subcomponentes/' + $("#Comp").val(),
            success: function(data) {
                $('#SComp').replaceWith(data);
            }
       });
    });
    
    $("#IP_TI").keyup(function(){ 
    var suma = parseInt($("#IP_TI").val()) + parseInt($("#IP_TII").val()) + parseInt($("#IP_TIII").val()) + parseInt($("#IP_TIV").val());
    $('#IP_TOTAL').val(suma);
      });
        
    $("#IP_TII").keyup(function(){
    var suma = parseInt($("#IP_TI").val()) + parseInt($("#IP_TII").val()) + parseInt($("#IP_TIII").val()) + parseInt($("#IP_TIV").val());
    $('#IP_TOTAL').val(suma);
        });
    
    $("#IP_TIII").keyup(function() {
    var suma = parseInt($("#IP_TI").val()) + parseInt($("#IP_TII").val()) + parseInt($("#IP_TIII").val()) + parseInt($("#IP_TIV").val());
    $('#IP_TOTAL').val(suma);
    });
    
    $("#IP_TIV").keyup(function(){
    var suma = parseInt($("#IP_TI").val()) + parseInt($("#IP_TII").val()) + parseInt($("#IP_TIII").val()) + parseInt($("#IP_TIV").val());
    $('#IP_TOTAL').val(suma);
    });
        
        
    $("#PESO_TI_BID").keyup(function(){ 
    var suma = parseInt($("#PESO_TI_BID").val()) + parseInt($("#PESO_TII_BID").val()) + parseInt($("#PESO_TIII_BID").val()) + parseInt($("#PESO_TIV_BID").val());
    $('#PESO_TOTFUE_BID').val(suma);
      });
        
    $("#PESO_TII_BID").keyup(function(){
    var suma = parseInt($("#PESO_TI_BID").val()) + parseInt($("#PESO_TII_BID").val()) + parseInt($("#PESO_TIII_BID").val()) + parseInt($("#PESO_TIV_BID").val());
    $('#PESO_TOTFUE_BID').val(suma);
        });
    
    $("#PESO_TIII_BID").keyup(function() {
    var suma = parseInt($("#PESO_TI_BID").val()) + parseInt($("#PESO_TII_BID").val()) + parseInt($("#PESO_TII_BID").val()) + parseInt($("#PESO_TIV_BID").val());
    $('#PESO_TOTFUE_BID').val(suma);
    });
    
    $("#PESO_TIV_BID").keyup(function(){
    var suma = parseInt($("#PESO_TI_BID").val()) + parseInt($("#PESO_TII_BID").val()) + parseInt($("#PESO_TIII_BID").val()) + parseInt($("#PESO_TIV_BID").val());
    $('#PESO_TOTFUE_BID').val(suma);
    });
    
    
    $("#PESO_TI_PYME").keyup(function(){ 
    var suma = parseInt($("#PESO_TI_PYME").val()) + parseInt($("#PESO_TII_PYME").val()) + parseInt($("#PESO_TIII_PYME").val()) + parseInt($("#PESO_TIV_PYME").val());
    $('#PESO_TOTFUE_PYME').val(suma);
      });
        
    $("#PESO_TII_PYME").keyup(function(){
    var suma = parseInt($("#PESO_TI_PYME").val()) + parseInt($("#PESO_TII_PYME").val()) + parseInt($("#PESO_TIII_PYME").val()) + parseInt($("#PESO_TIV_PYME").val());
    $('#PESO_TOTFUE_PYME').val(suma);
        });
    
    $("#PESO_TIII_PYME").keyup(function() {
    var suma = parseInt($("#PESO_TI_PYME").val()) + parseInt($("#PESO_TII_PYME").val()) + parseInt($("#PESO_TII_PYME").val()) + parseInt($("#PESO_TIV_PYME").val());
    $('#PESO_TOTFUE_PYME').val(suma);
    });
    
    $("#PESO_TIV_PYME").keyup(function(){
    var suma = parseInt($("#PESO_TI_PYME").val()) + parseInt($("#PESO_TII_PYME").val()) + parseInt($("#PESO_TIII_PYME").val()) + parseInt($("#PESO_TIV_PYME").val());
    $('#PESO_TOTFUE_PYME').val(suma);
    });
    
        $("#PESO_TI_BNA").keyup(function(){ 
    var suma = parseInt($("#PESO_TI_BNA").val()) + parseInt($("#PESO_TII_BNA").val()) + parseInt($("#PESO_TIII_BNA").val()) + parseInt($("#PESO_TIV_BNA").val());
    $('#PESO_TOTFUE_BNA').val(suma);
      });
        
    $("#PESO_TII_BNA").keyup(function(){
    var suma = parseInt($("#PESO_TI_BNA").val()) + parseInt($("#PESO_TII_BNA").val()) + parseInt($("#PESO_TIII_BNA").val()) + parseInt($("#PESO_TIV_BNA").val());
    $('#PESO_TOTFUE_BNA').val(suma);
        });
    
    $("#PESO_TIII_BNA").keyup(function() {
    var suma = parseInt($("#PESO_TI_BNA").val()) + parseInt($("#PESO_TII_BNA").val()) + parseInt($("#PESO_TII_BNA").val()) + parseInt($("#PESO_TIV_BNA").val());
    $('#PESO_TOTFUE_BNA').val(suma);
    });
    
    $("#PESO_TIV_BNA").keyup(function(){
    var suma = parseInt($("#PESO_TI_BNA").val()) + parseInt($("#PESO_TII_BNA").val()) + parseInt($("#PESO_TIII_BNA").val()) + parseInt($("#PESO_TIV_BNA").val());
    $('#PESO_TOTFUE_BNA').val(suma);
    }); 
        
    $("#PESO_TOTFUE_BID").keyup(function(){ 
    var suma = parseInt($("#PESO_TOTFUE_BID").val()) + parseInt($("#PESO_TOTFUE_BNA").val()) + parseInt($("#PESO_TOTFUE_PYME").val());
    $('#PESO_TOTAL').val(suma);
      });
    $("#PESO_TOTFUE_BNA").keyup(function(){ 
    var suma = parseInt($("#PESO_TOTFUE_BID").val()) + parseInt($("#PESO_TOTFUE_BNA").val()) + parseInt($("#PESO_TIII_PYME").val());
    $('#PESO_TOTAL').val(suma);
      });
    $("#PESO_TOTFUE_PYME").keyup(function(){ 
    var suma = parseInt($("#PESO_TOTFUE_BID").val()) + parseInt($("#PESO_TOTFUE_BNA").val()) + parseInt($("#PESO_TOTFUE_PYME").val());
    $('#PESO_TOTAL').val(suma);
      });
      
  
    
    
});
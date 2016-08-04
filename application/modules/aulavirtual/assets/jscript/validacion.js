$(document).ready(function() {
    
    var wrapper = $(".append"); //Fields wrapper

    $("#cantidad_empleados").change(function() {
        
        $('.empleados').remove();
        
       for (i = 0; i < ($("#cantidad_empleados").val()); i++) { 
             
        $(wrapper).append("<div class='form-group empleados'><label class='col-sm-2 control-label'>Datos del Empleado</label><div class='input_fields_wrap col-sm-3'><div><input type='text' name='integrante["+i+"][cuil]' class='form-control r20 dnis' placeholder='CUIL'></div></div><div class='input_fields_wrap col-sm-3'><div><input type='email' name='integrante["+i+"][email]' class='form-control r20' placeholder='Email del empleado'></div></div><div class='input_fields_wrap col-sm-3'><div><input type='text' name='integrante["+i+"][area]' class='form-control r20 nya' placeholder='Area en la que se desempeña' required></div></div><div class='input_fields_wrap col-sm-8 col-sm-offset-2'><div></div></div></div>"); //add input box
       }
            
    });
    
    $.validator.addMethod("valueNotEquals", function(value, element, arg){
        return arg != value;
        }, "Seleccione una opción válida");

    $('#form_profile').validate({
        // debug:true,
        invalidHandler: function(event, validator) {
        var errors = validator.numberOfInvalids();
        },
        
        checkForm: function() {
        this.prepareForm();
        for ( var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++ ) {
        if (this.findByName( elements[i].name ).length != undefined && this.findByName( elements[i].name ).length > 1) {
        for (var cnt = 0; cnt < this.findByName( elements[i].name ).length; cnt++) {
        this.check( this.findByName( elements[i].name )[cnt] );
        }
        } else {
        this.check( elements[i] );
        }
        }
        return this.valid();
        },
        
        rules: {
            cantidad_empleados: { valueNotEquals: "default" },
            check: {
                required: true,
                maxlength: 2
            },
            name: {required : true },
            lastname: {required : true },
            razon_social: {required : true },
            address: {required : true},
            email: {required : true},
        },
        messages: {
            name: {
                remote: "El usuario no se encuentra registrado"
            },
            lastname: {
                remote: "El usuario ya se encuentra registrado en otra inscripción"
            },
            address: {
                required: "Este campo es obligatorio"
            },
            check: {
                required: "Tenés que aceptar el manifiesto"
            }
        }
    });

});
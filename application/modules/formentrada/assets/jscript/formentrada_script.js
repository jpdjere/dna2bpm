/**
 * dna2/inbox JS
 * 
 **/
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
   
            $("#commentForm").validate({
			rules: {
			    prestamo:{
			        required: true
			    },
			     monto:{
			        required: true
			    },
			     situacion:{
			        required: true
			    },
			     vincu:{
			        required: true
			    },
				nombre: {
				required: true,
				minlength: 3
				},
				forma: {
					required: true,
					minlength: 2
				},
				cuit: {
					required: true,
					minlength: 13
					
				},
				provincia: {
					required: true
					
				},
				municipio: {
					required: true,
					minlength: 3
					
				},
				direc: {
					required: true,
					minlength: 3
					
				},
				contact: {
					required: true,
					minlength: 3
					
				},
				cargo: {
					required: true,
					minlength: 3
					
				},
				telefono: {
					required: true,
					number:true,
					minlength: 8
					
				},
				email: {
					required: true,
					email: true
				},
				email1: {
					required: true,
					email: true
				}
			},
			messages: {
			    prestamo:{
			        required: "Por favor seleccione una opción."
			    },
			     monto:{
			        required: "Por favor seleccione una opción."
			    },
			     situacion:{
			        required: "Por favor seleccione una opción."
			    },
			     vincu:{
			        required: "Por favor seleccione una opción."
			    },
			    
				nombre: {
					required: "Por favor ingrese Nombre o Razón Social de la Empresa.",
					minlength: "Por favor ingrese al menos 3 caracteres."
				},
				forma: {
					required: "Por favor ingrese Forma Jurídica de la Empresa.",
					minlength: "Por favor ingrese al menos 2 caracteres."
				},
				cuit: {
					required: "Por favor ingrese el CUIT de la Empresa.",
					minlength: "Por favor ingrese al menos 11 números.",
					
				},
				provincia: {
					required: "Por favor ingrese la Provincia."
				},
				municipio: {
					required: "Por favor ingrese la Localidad.",
					minlength: "Por favor ingrese al menos 3 caracteres."
				},
				direc: {
					required: "Por favor ingrese la Dierección.",
					minlength: "Por favor ingrese al menos 3 caracteres."
					
				},
				contact: {
					required: "Por favor ingrese el Nombre de Contacto.",
					minlength: "Por favor ingrese al menos 3 caracteres."
					
				},
				cargo: {
					required: "Por favor ingrese el Cargo del Contacto.",
					minlength: "Por favor ingrese al menos 3 caracteres."
					
				},
				telefono: {
					required: "Por favor ingrese un teléfono de Contacto.",
					number:"El teléfono debe ser numérico.",
					minlength: "Por favor ingrese al menos 8 números."
					
				},
			
				email: "Por favor ingrese una dirección de email válida",
				email1: "Por favor ingrese una dirección de email válida",
			
			}
			
			
		});
            $("#cuit").mask("99-99999999-9",{placeholder:""});
            $('#commentForm').submit(function(e) {
                
                e.preventDefault();
                if ($("#commentForm").valid() == false){
                    alert('Por favor complete los campos solicitados!');
                    
                }else{
                    var fields = $("#commentForm").serializeArray();
                    
                    var email_check = fields[13].value;
                    var email_check1 = fields[14].value;
                    var flag_final; 
                    if(email_check != email_check1){
                       alert('Las direcciones de email no coinciden!'); 
                    } else{
                    
                    
                    //console.log(fields[0].name,fields[0].value );
                    $.ajax({
                        type: "POST",
                       
                        url: base_url + 'formentrada/formentrada/segundo_formulario/',
                        data: fields,
                        dataType : "json",
                        success: function(result) {
                            $("#col3").html(result.tabla);
                            formulario_2(fields);
                            
                            
                            }
                        });
                    }
                    
                }
               
                
                
                
            });
            

    function formulario_2(fields){        
            
        $("#commentForm2").validate({
			rules: {
			    tipo_pres:{
			        required: true
			    },
			     monto_total:{
			        required: true/*,
			        number:true*/
			    },
			     financiamiento:{
			        required: true/*,
			        number:true*/
			    },
			     balance_1:{
			        required: true/*,
			        number:true*/
			    },
				balance_2: {
    				required: true/*,
    				number:true*/
				},
				balance_3: {
					required: true/*,
					number:true*/
				},
				afip: {
					required: true,
					minlength: 3
				},
				sector: {
					required: true
					
				},/*
				codigo_act: {
					required: true,
					minlength: 3
				},
				tipo_act: {
					required: true,
					minlength: 3
				}*/
			    
			},
			
			messages: {
			    tipo_pres:{
			        required: "Por favor seleccione una opción."
			    },
			     monto_total:{
			        required: "Por favor ingrese el Monto Total del Proyecto.",
			        //number:"La información ingresada debe ser del tipo numérico."
			    },
			     financiamiento:{
			        required: "Por favor ingrese el Financiamiento a solicitar.",
			        number:"La información ingresada debe ser del tipo numérico."
			    },
			     balance_1:{
			        required: "Por favor ingrese Ventas (Último Balance/Año).",
			        number:"La información ingresada debe ser del tipo numérico."
			    },
			    
				balance_2: {
					required: "Por favor ingrese Ventas (Ante Último Balance/Año).",
					number:"La información ingresada debe ser del tipo numérico."
				},
				balance_3: {
					required: "Por favor ingrese Ventas (Ante Penúltimo Balance/Año).",
					number:"La información ingresada debe ser del tipo numérico."
				},
				afip: {
					required: "Por favor ingrese Fecha de Inscripción en AFIP de la actividad financiada.",
					minlength: "Por favor ingrese al menos 3 caracteres."
					
				},
				sector: {
					required: "Por favor ingrese Sector de Actividad."
					
				},/*
				codigo_act: {
					required: "Por favor ingrese Código de la actividad a ser financiada, según constancia AFIP (F-883).",
					minlength: "Por favor ingrese al menos 3 caracteres."
				},
				tipo_act: {
					required: "Por favor ingrese Tipo de Actividad a ser financiada, según Constancia AFIP (F-883).",
					minlength: "Por favor ingrese al menos 3 caracteres."
				},*/
			
			}
		});
    
    $('#monto_total').priceFormat({
    prefix: '',
    centsLimit: 0,
    centsSeparator: ',',
    thousandsSeparator: '.'
    });
    
    $('#financiamiento').priceFormat({
    prefix: '',
    centsLimit: 0,
    centsSeparator: ',',
    thousandsSeparator: '.'
    });
    $('#balance_1').priceFormat({
    prefix: '',
    centsLimit: 0,
    centsSeparator: ',',
    thousandsSeparator: '.'
    });
    $('#balance_2').priceFormat({
    prefix: '',
    centsLimit: 0,
    centsSeparator: ',',
    thousandsSeparator: '.'
    });
    $('#balance_3').priceFormat({
    prefix: '',
    centsLimit: 0,
    centsSeparator: ',',
    thousandsSeparator: '.'
    });
   
    $("#afip").mask("99-99-9999",{placeholder:" "});
    
    $("#codigo_act").mask("999999",{completed:function(){buscarclanae( $("#codigo_act").val())}});
    
    /*
    $('#codigo_act').on('input', function() {
					var input=$(this);
					var name_val=input.val();
					$("#codigo_act").mask("999999",{placeholder:" "});
					if(name_val.length == 6){
                        console.log('6 Caracteres!');					    
					}
					
				});
    
    */
    
    
    $(".calendar").datepicker();

        var changeYear = $( ".calendar" ).datepicker( "option", "changeYear" );
        var changeMonth = $( ".calendar" ).datepicker( "option", "changeMonth" );
        var cant_arch = 0;
        $( ".calendar" ).datepicker( "option", "changeMonth", true );
        $( ".calendar" ).datepicker( "option", "changeYear", true );
        //$( ".calendar" ).datepicker( "option", "yearRange", "1920:2013" );
        
        
        $(function() {
            $('#afip').datepicker({
                maxDate:0,
                changeYear: true,
                yearRange: "-100:+0",
                dateFormat: "dd-mm-yy",
                firstDay: 1,
                dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
                monthNames: 
                    ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
                    "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                monthNamesShort: 
                    ["Ene", "Feb", "Mar", "Abr", "May", "Jun",
                    "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
        });
    
    });
    
    
    
    
    $('#commentForm2').submit(function(e) {
                
                e.preventDefault();
                if ($("#commentForm2").valid() == false){
                    alert('Por favor complete los campos solicitados!');
                    
                }else{
                    var fields2 = $("#commentForm2").serializeArray();
                    
                    
                    var clasifica = '';
                    var prestamo = fields[0].value;
                    var monto = fields[1].value;
                    var situacion = fields[2].value;
                    var email = fields[13].value;
                    
                if(prestamo == 'NO'){
                    clasifica = 'FONAPYME';
                }else{
                    if(monto == 'SI'){
                        clasifica = 'FONAPYME';
                    }else{
                        if(situacion == 'SI'){
                            clasifica = 'FONAPYME';
                        }else{
                            clasifica = 'BANCARIO';
                        }
                    }
                    
                }
                  
                //alert(clasifica);
                
                 //console.log(base_url);
                var flag =0;
                var flag1=0;
                var flag2=0;
                var flag3=0;
                var tipo_pres = fields2[0].value;
                var monto_total = fields2[1].value;
                var financiamiento = fields2[2].value;
                var balance_1 = fields2[3].value;
                var balance_2 = fields2[4].value;
                var balance_3 = fields2[5].value;
                
                
                var monto_total = replaceAll(monto_total,".", "");
                var financiamiento = replaceAll(financiamiento,".", "");
                var balance_1 = replaceAll(balance_1,".", "");
                var balance_2 = replaceAll(balance_2,".", "");
                var balance_3 = replaceAll(balance_3,".", "");
                
                var fecha = fields2[6].value;
                var sector = fields2[7].value;
                var financiamiento_aprob;
                var return_cargaok = false;
                
                
                var error_dos_y = 'NO';
                var error_balance_prom = 'NO';
                var error_dos_balances = 'NO';
                
                var balance_prom = (balance_1 / 3 + balance_2 / 3 + balance_3 / 3 );
                if (clasifica== 'FONAPYME'){
                    if(tipo_pres == 'Capital de trabajo'){
                        financiamiento_aprob = (monto_total * 1);
                        if( financiamiento < 100000 || financiamiento >1500000 || financiamiento > financiamiento_aprob){
                            alert('El monto del financiamiento a solicitar puede ser hasta el 100% del monto total del proyecto, con un mínimo de $100 mil y sin superar los $1,5 Millones.');
                            flag = 1;
                            
                        }
                        
                        
                    }else{
                        financiamiento_aprob = (monto_total * 0.70);
                        if(financiamiento > financiamiento_aprob || financiamiento < 100000 || financiamiento >3000000){
                            alert('El monto del financiamiento a solicitar puede ser hasta el 70% del monto total del proyecto, con un mínimo de $100 mil y sin superar los $3 Millones.');
                            flag = 1;
                            
                        }
                    }
                    
                    
                    if(flag == 0){
                    
                        if(balance_2 == 0 || balance_1 == 0){
                            //alert('EN VIRTUD DE SU ANTIGÜEDAD Y DE LA INFORMACIÓN SUMINISTRADA POR USTED, NOS PONDREMOS EN CONTACTO PARA OFRECERLE ALGUNA DE LAS HERRAMIENTAS VIGENTES DEL MINISTERIO DE PRODUCCIÓN, A LAS QUE POTENCIALMENTE PODRÍA ACCEDER');
                            error_dos_balances = 'SI';
                            flag1 = 1;
                            
                        }
                        
                        //var dt = new Date();

                        
                        //console.log(fecha);
                        //console.log(moment());
                        
                        var fecha_check = moment(fecha,'DD-MM-YYYY');
                        var two_years_check = moment();
                        
                        
                        var dif= two_years_check.diff(fecha_check,'days');
                        //console.log(dif);
              
                        
                        if(dif < 730){
                            //alert('EN VIRTUD DE SU ANTIGÜEDAD Y DE LA INFORMACIÓN SUMINISTRADA POR USTED, NOS PONDREMOS EN CONTACTO PARA OFRECERLE ALGUNA DE LAS HERRAMIENTAS VIGENTES DEL MINISTERIO DE PRODUCCIÓN, A LAS QUE POTENCIALMENTE PODRÍA ACCEDER');
                            error_dos_y = 'SI';
                            flag2 = 1;
                            
                        }
                        
                        
                        
                        
                        
                        
                        
                        switch (sector){
                            case 'Industria, Agroindustria y Mineria':
                                if(balance_prom >270000000){
                                    //alert('NO CALIFICA COMO PYME DE ACUERDO AL PROMEDIO DE FACTURACIÓN DECLARADA. LA MISMA PARA INDUSTRIA, AGROINDUSTRIA Y MINERÍA DEBE SER MENOR A $ 270.000.000');
                                     //alert('SEGÚN SU FACTURACIÓN, SU EMPRESA NO CLASIFICA COMO PYME INCUMPLIENDO CON LAS CARACTERÍSTICAS DEL PROGRAMA FONAPYME. SIN EMBARGO, LA INFORMACIÓN SUMINISTRADA POR USTED, NOS PERMITIRÁ OFRECERLE ALGUNA DE LAS HERRAMIENTAS VIGENTES DEL MINISTERIO DE PRODUCCIÓN, A LAS QUE POTENCIALMENTE PODRÍA ACCEDER');
                                    error_balance_prom = 'SI';
                                    flag3 = 1;
                                }
                                break;
                            case 'Construccion':
                                if(balance_prom >134000000){
                                    //alert('NO CALIFICA COMO PYME DE ACUERDO AL PROMEDIO DE FACTURACIÓN DECLARADA. LA MISMA PARA CONSTRUCCIÓN DEBE SER MENOR A $ 134.000.000');
                                    //alert('SEGÚN SU FACTURACIÓN, SU EMPRESA NO CLASIFICA COMO PYME INCUMPLIENDO CON LAS CARACTERÍSTICAS DEL PROGRAMA FONAPYME. SIN EMBARGO, LA INFORMACIÓN SUMINISTRADA POR USTED, NOS PERMITIRÁ OFRECERLE ALGUNA DE LAS HERRAMIENTAS VIGENTES DEL MINISTERIO DE PRODUCCIÓN, A LAS QUE POTENCIALMENTE PODRÍA ACCEDER');
                                    error_balance_prom = 'SI';
                                    flag3 = 1;
                                }
                                break;
                            case 'Servicios Industriales':
                                if(balance_prom >91000000){
                                    //alert('NO CALIFICA COMO PYME DE ACUERDO AL PROMEDIO DE FACTURACIÓN DECLARADA. LA MISMA PARA SERVICIOS INDUSTRIALES DEBE SER MENOR A $ 91.000.000');
                                    //alert('SEGÚN SU FACTURACIÓN, SU EMPRESA NO CLASIFICA COMO PYME INCUMPLIENDO CON LAS CARACTERÍSTICAS DEL PROGRAMA FONAPYME. SIN EMBARGO, LA INFORMACIÓN SUMINISTRADA POR USTED, NOS PERMITIRÁ OFRECERLE ALGUNA DE LAS HERRAMIENTAS VIGENTES DEL MINISTERIO DE PRODUCCIÓN, A LAS QUE POTENCIALMENTE PODRÍA ACCEDER');
                                    error_balance_prom = 'SI';
                                    flag3 = 1;
                                }
                                break;
                            case 'Comercio':
                                if(balance_prom >343000000){
                                    //alert('NO CALIFICA COMO PYME DE ACUERDO AL PROMEDIO DE FACTURACIÓN DECLARADA. LA MISMA PARA COMERCIO DEBE SER MENOR A $ 343.000.000');
                                    //alert('SEGÚN SU FACTURACIÓN, SU EMPRESA NO CLASIFICA COMO PYME INCUMPLIENDO CON LAS CARACTERÍSTICAS DEL PROGRAMA FONAPYME. SIN EMBARGO, LA INFORMACIÓN SUMINISTRADA POR USTED, NOS PERMITIRÁ OFRECERLE ALGUNA DE LAS HERRAMIENTAS VIGENTES DEL MINISTERIO DE PRODUCCIÓN, A LAS QUE POTENCIALMENTE PODRÍA ACCEDER');
                                    error_balance_prom = 'SI';
                                    flag3 = 1;
                                }
                                break;
                            case 'Agropecuario':
                                if(balance_prom >82000000){
                                    //alert('NO CALIFICA COMO PYME DE ACUERDO AL PROMEDIO DE FACTURACIÓN DECLARADA. LA MISMA PARA AGROPECUARIO DEBE SER MENOR A $ 82.000.000');
                                    //alert('SEGÚN SU FACTURACIÓN, SU EMPRESA NO CLASIFICA COMO PYME INCUMPLIENDO CON LAS CARACTERÍSTICAS DEL PROGRAMA FONAPYME. SIN EMBARGO, LA INFORMACIÓN SUMINISTRADA POR USTED, NOS PERMITIRÁ OFRECERLE ALGUNA DE LAS HERRAMIENTAS VIGENTES DEL MINISTERIO DE PRODUCCIÓN, A LAS QUE POTENCIALMENTE PODRÍA ACCEDER');
                                    error_balance_prom = 'SI';
                                    flag3 = 1;
                                }
                                break;
                        }
                    
                    }   
                        
                    
                        
                    }
                    
                    if(flag == 0 ){
                        if( flag1 == 0 && flag2 == 0 && flag3 == 0){
                        if (clasifica == 'FONAPYME'){
                            alert('EN PRINCIPIO, CUMPLE CON LAS CARACTERÍSTICAS BÁSICAS DEL PROGRAMA FONAPYME. SE HA ENVIADO A SU CASILLA DE CORREO ELECTRÓNICO EL FORMULARIO PARA PARTICIPAR DEL CONCURSO PÚBLICO');    
                            
                            
                            if(tipo_pres == 'Capital de trabajo'){
                                $.ajax({
                                    type: "POST",
                                   
                                    url: base_url + 'formentrada/formentrada/send_mail_1/',
                                    //data: clasifica,
                                    data: {'email':email},
                                    dataType : "json",
                                    success: function(result) {
                             //$("#col3").html('<div>Gracias por contactarte con el Ministerio de Producción.</div>');           //$("#col3").html('<div>Gracias por contactarte con el Ministerio de Producción.</div>');
                                        //formulario_2();
                                        
                                        //console.log(result);
                                        }
                                });
                            }else{
                                $.ajax({
                                    type: "POST",
                                   
                                    url: base_url + 'formentrada/formentrada/send_mail/',
                                    //data: clasifica,
                                    data: {'email':email},
                                    dataType : "json",
                                    success: function(result) {
                             //$("#col3").html('<div>Gracias por contactarte con el Ministerio de Producción.</div>');           //$("#col3").html('<div>Gracias por contactarte con el Ministerio de Producción.</div>');
                                        //formulario_2();
                                        
                                        //console.log(result);
                                        }
                                });
                            }
                            
                            
                            
                        }
                        
                        if (clasifica == 'BANCARIO'){
                            alert('NO CUMPLE CON LAS CARACTERÍSTICAS DEL PROGRAMA FONAPYME. SIN EMBARGO, LA INFORMACIÓN SUMINISTRADA POR USTED, NOS PERMITIRÁ OFRECERLE ALGUNA DE LAS HERRAMIENTAS VIGENTES DEL MINISTERIO DE PRODUCCIÓN, A LAS QUE POTENCIALMENTE PODRÍA ACCEDER'); 
                        }
                        }else{
                            
                            var mensaje_error = '';
                            
                            if(flag1 == 1 || flag2 ==1){
                                mensaje_error = 'EN VIRTUD DE SU ANTIGÜEDAD Y DE LA INFORMACIÓN SUMINISTRADA POR USTED, NOS PONDREMOS EN CONTACTO PARA OFRECERLE ALGUNA DE LAS HERRAMIENTAS VIGENTES DEL MINISTERIO DE PRODUCCIÓN, A LAS QUE POTENCIALMENTE PODRÍA ACCEDER';
                            } else{
                                mensaje_error = 'SEGÚN SU FACTURACIÓN, SU EMPRESA NO CLASIFICA COMO PYME INCUMPLIENDO CON LAS CARACTERÍSTICAS DEL PROGRAMA FONAPYME. SIN EMBARGO, LA INFORMACIÓN SUMINISTRADA POR USTED, NOS PERMITIRÁ OFRECERLE ALGUNA DE LAS HERRAMIENTAS VIGENTES DEL MINISTERIO DE PRODUCCIÓN, A LAS QUE POTENCIALMENTE PODRÍA ACCEDER';
                            }
                            alert(mensaje_error);
                            
                            
                        }   
                    //console.log(fields2);
                    
                    
                    
                    //console.log(clanae_cargado);
                    $.ajax({
                        type: "POST",
                       
                        url: base_url + 'formentrada/formentrada/guardar_datos/',
                        //data: clasifica,
                        data: {'clasifica':clasifica,'fields':fields,'fields2':fields2,'error_dos_y':error_dos_y,'error_dos_balances':error_dos_balances,'error_balance_prom':error_balance_prom},
                        dataType : "json",
                        
                        success: function(result) {
                           
                            //formulario_2();
                            
                            //console.log(result);
                            }
                        });
                        //console.log('result');
                         $("#col3").html('<div align="center"><H1>Gracias por contactarte con el Ministerio de Producción.</H1></div>');
                        
                    }
                    
                    }
                }
               
                
                
                
            );
    
   
    }
    
    function replaceAll( text, busca, reemplaza ){
        while (text.toString().indexOf(busca) != -1)
        text = text.toString().replace(busca,reemplaza);
        return text;
    }
    
    function buscarclanae(clanae){
        $.ajax({
                type: "POST",
                url: base_url + 'formentrada/formentrada/buscar_clanae/',
                //data: clasifica,
                data: {'clanae':clanae},
                dataType : "json",
                success: function(result) {
                            $("#col6").html("<font color='red'>" + result + "</font>");           //$("#col3").html('<div>Gracias por contactarte con el Ministerio de Producción.</div>');
                            return result; 
                                        //formulario_2();
                                        
                                        //console.log(result);
                }
                });
    }

    
            
});//            

 
  




<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>{title}</title>

        <!-- Bootstrap -->
        <link href="{base_url}/dashboard/assets/bootstrap-wysihtml5/css/bootstrap.min.css" rel="stylesheet">
        <link href="{base_url}/dashboard/assets/css/style.css" rel="stylesheet">
        <link href="{base_url}gestion/assets/css/style.css" rel="stylesheet">
        <!-- font Awesome -->
        <link href="{base_url}dashboard/assets/bootstrap-wysihtml5/css/font-awesome-4.4.0/css/font-awesome.css" rel="stylesheet" type="text/css" />

        <!-- Daterange picker -->
        <link href="{base_url}/dashboard/assets/bootstrap-wysihtml5/css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />

        <!-- overload css skins -->
        <link href="{base_url}/dashboard/assets/css/style.css" rel="stylesheet" type="text/css" />
        <!-- CSS:fullcalendar -->
        <link rel='stylesheet' type='text/css' href='{base_url}/dashboard/assets/bootstrap-wysihtml5/js/plugins/fullcalendar-2.3.1/fullcalendar.css' />
        <!-- CSS:daterangerpicker -->
        <link rel='stylesheet' type='text/css' href='{base_url}/dashboard/assets/bootstrap-wysihtml5/css/daterangepicker/daterangepicker.css' />


        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script> --
          <![endif]-->
    </head>

    <body>


        <div class='container'>
            <!-- ============= Barra Ministerio  -->

            <div class='row'>
                {logobar}
            </div>
            <!-- ============= Formulario  -->

            <div class='row'>
                <div class='col-md-12'>
                    <h2>EXPTE S01</h2>
                    <h3>Solicitud de anticipo de viaticos y ordenes de pasaje</h3>
                    <h4>Normativa: Decreto Nº 1343/74 compl.y modif. y/o Decreto Nº 2345/08</h4>

                    <!-- MSGs -->

                    <div id='msg_error' style="display:none;margin-top:20px">
                        <div class="alert alert-danger" role="alert">
                            Error
                        </div>
                    </div>

                    <div id='msg_ok' style="display:none;margin-top:20px">
                        <div class="alert alert-success" role="alert">
                            El formulario se ha enviado correctamente. Gracias!                            
                        </div>
                         <div id="MSG"></div>
                    </div>
                    
                   

                    <form method='post'>
                        <!-- Destino Interior/Internacional-->
                        <div class="form-group">
                            <label for="nombre">Destino Viaje</label>
                            <select id="destino" name="destino" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="interior">Interior</option>
                                <option value="exterior">Exterior</option>
                            </select>
                        </div>



                        <div class="row">
                            <div class='col-md-12'>
                                <label for="expte">EXPTE S01:</label>
                                <input class="form-control" id="expte" name="expte" placeholder="Ingrese el Nro. de Expte">
                            </div>
                        </div>

                        <!-- INTERIOR -->
                        <div id="interior">
                            <h4 class='text-info'><i class="fa fa-chevron-right text-info"></i> INTERIOR</h4>
                            <!-- Provincia  -->
                            <div class="form-group">
                                <label for="nombre">Provincia</label>
                                <select name="provincia" name="provincia" class="form-control">
                                    <option value="">Elija provincia</option>
                                    <option value="Buenos Aires">Buenos Aires</option>
                                    <option value="CABA">CABA</option>
                                    <option value="Catamarca">Catamarca</option>
                                    <option value="Chaco">Chaco</option>
                                    <option value="Chubut">Chubut</option>
                                    <option value="Cordoba">Córdoba</option>
                                    <option value="Corrientes">Corrientes</option>
                                    <option value="Entre Rios">Entre Rios</option>
                                    <option value="Formosa">Formosa</option>
                                    <option value="Jujuy">Jujuy</option>
                                    <option value="La Pampa">La Pampa</option>
                                    <option value="La Rioja">La Rioja</option>
                                    <option value="Mendoza">Mendoza</option>
                                    <option value="Misiones">Misiones</option>
                                    <option value="Neuquen">Neuquén</option>
                                    <option value="Rio Negro">Rio Negro</option>
                                    <option value="Salta">Salta</option>
                                    <option value="San Juan">San Juan</option>
                                    <option value="San Luis">San Luis</option>
                                    <option value="Santa Cruz">Santa Cruz</option>
                                    <option value="Santa Fe">Santa Fe</option>
                                    <option value="Santiago del Estero">Santiago del Estero</option>
                                    <option value="Tierra del Fuego">Tierra del Fuego</option>
                                    <option value="Tucuman">Tucumán</option>
                                </select>
                            </div>

                            <!-- Ciudad  -->
                            <div class="row">
                                <div class='col-md-6'>
                                    <label for="ciudad">Ciudad</label>
                                    <input type="text" class="form-control" id="ciudad" name="ciudad" placeholder="Ciudad">
                                </div>


                                <div class='col-md-6'>
                                    <label for="transporte">Medio de Trasporte</label>
                                    <select name="transporte" name="transporte" class="form-control">
                                        <option value="">Seleccione</option>
                                        <option value="avion">Avion</option>
                                        <option value="combi">Combi</option>
                                        <option value="auto">Auto</option>
                                        <option value="micro">Micro</option>
                                    </select>
                                </div>
                            </div>


                        </div>

                        <div id=exterior>
                            <h4 class='text-info'><i class="fa fa-chevron-right text-info"></i> EXTERIOR</h4>
                            <div class="row">
                                <div class='col-md-6'>
                                    <label for="lugar_destino">Lugar de Destino</label>
                                    <input type="text" class="form-control" id="lugar_destino" name="lugar_destino" placeholder="Lugar de Destino">
                                </div>

                                <div class='col-md-6'>
                                    <label for="transporte_exterior">Medio de Trasporte</label>
                                    <select name="transporte_exterior" name="transporte_exterior" class="form-control">
                                        <option value="">Seleccione</option>
                                        <option value="avion">Avion</option>
                                        <option value="buquebus">Buquebus</option>
                                    </select>
                                </div>



                                <div class='col-md-6'>
                                    <label for="tipo_de_cambio">Tipo de Cambio</label>
                                    <input type="text" class="form-control" id="tipo_de_cambio" name="tipo_de_cambio" placeholder="Tipo de Cambio">
                                </div>

                                <div class='col-md-6'>
                                    <label for="costo_moneda_extrangera">Costo en Moneda Extrangera</label>
                                    <input type="text" class="form-control" id="costo_moneda_extrangera" name="costo_moneda_extrangera" placeholder="0.00">
                                </div>
                            </div>

                        </div>

                        <!-- Fecha/Hora  -->

                        <div class="form-group">
                            <label>Fecha Desde/Hasta</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input class="form-control pull-right range" name="event-interval" id="event-interval" type="text" placeholder="&#123;lang interval&#125;" value="27/04/2016 15:00 - 27/04/2016 15:30">
                            </div>
                            <!-- /.input group -->
                        </div>


                        <div class="row">
                            <div class='col-md-12'>
                                <label for="motivo">Motivo de la comisión de servicio</label>
                                <textarea class="form-control" id="motivo" name="motivo" placeholder="Motivode la comisión de servicio"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class='col-md-12'>
                                <label for="dependencia">Dependencia que requiere el servicio</label>
                                <input type="text" class="form-control" id="dependencia" name="dependencia" value="SECRETARIA DE EMPRENDEDORES Y DE LA PEQUEÑA Y MEDIANA

                                       EMPRESA">
                            </div>


                        </div>

                        <!-- ADD -->
                        <div class="form-group">
                            <label>Agentes Habilitados</label>
                            <div class="input-group">
                                <select class="form-control" id="select_group">
                                    <option value="">Seleccione</option>
                                    {groupagents}
                                </select>
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default dropdown-toggle" id="add_group" title="Add group"> <i class="fa fa-plus"></i></button>
                                </div>

                            </div>
                        </div>
                        <div class="form-group" id="groups_box">

                        </div>

                        <!-- pasaje -->
                        <div class="row">
                            <div class='col-md-6'>
                                <label for="pasaje">Pasaje</label>
                                <input type="text" class="form-control" id="pasaje" name="pasaje" placeholder="Pasaje">
                            </div>

                            <div class='col-md-3'>
                                <label for="importe_pasaje">Importe Pasaje</label>
                                <input type="text" class="form-control" id="importe_pasaje" name="importe_pasaje" placeholder="0.00">
                            </div>

                            <div class='col-md-3'>
                                <label for="gastos_eventuales">Gastos Eventuales</label>
                                <input type="text" class="form-control" id="gastos_eventuales" name="gastos_eventuales" placeholder="0.00">
                            </div>
                        </div>

                        <div id=exterior>

                        </div>


                        <div class="row">
                            <div class='col-md-12'>
                                <label for="observaciones">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" placeholder="observaciones..."></textarea>
                                <br />
                            </div>
                        </div>
                </div>




                <!-- Send -->
                <div id="send_button">
                    <div class="form-group" style="margin-top:15px">
                        <input type="submit" class="form-control btn btn-primary" value="Enviar">
                    </div>
                </div>


                </form>





            </div>
        </div>
        <!-- JS Global -->
        <script>
            //-----declare global vars
            var base_url = '{base_url}';
        </script>

        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="{base_url}/jscript/jquery/jquery.min.js"></script>
        <script src="{base_url}/dashboard/assets/bootstrap-wysihtml5/js/bootstrap.min.js"></script>
        <script src="{base_url}/jscript/jquery/plugins/jquery-validation-1.15.0/jquery.validate.min.js"></script>
        <script src="{base_url}/jscript/jquery/plugins/jquery-validation-1.15.0/localization/messages_es_AR.js"></script>
        <script src="{base_url}/gestion/assets/jscript/form_viaticos.js"></script>

        <!--CALENDAR -->
        <script src='{base_url}/jscript/jquery/ui/jquery-ui-1.10.2.custom/jquery-ui-1.10.2.custom.min.js'></script>
        <script src='{base_url}/dashboard/assets/bootstrap-wysihtml5/js/AdminLTE/app.js'></script>
        <script src='{base_url}/jscript/jquery/plugins/Form/jquery.form.min.js'></script>
        <script src='{base_url}/dashboard/assets/bootstrap-wysihtml5/js/plugins/fullcalendar-2.3.1/lib/moment.min.js'></script>
        <script src='{base_url}/dashboard/assets/bootstrap-wysihtml5/js/plugins/fullcalendar-2.3.1/fullcalendar.min.js'></script>
        <script src='{base_url}/dashboard/assets/bootstrap-wysihtml5/js/plugins/fullcalendar-2.3.1/lang-all.js'></script>
        <script src='{base_url}/dashboard/assets/bootstrap-wysihtml5/js/plugins/daterangepicker/daterangepicker.js'></script>


        <!-- JS custom -->
        <!-- JS:calendar-JS -->
        <script src='{base_url}/calendar/assets/jscript/app.js'></script>


    </body>

</html>
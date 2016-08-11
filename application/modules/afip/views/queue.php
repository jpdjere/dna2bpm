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
    <link href="{base_url}afip/assets/css/style.css" rel="stylesheet">
    <link href="{base_url}afip/assets/css/dashboard.css" rel="stylesheet">   
    <!-- font Awesome -->
    <link href="{base_url}dashboard/assets/bootstrap-wysihtml5/css/font-awesome-4.4.0/css/font-awesome.css" rel="stylesheet" type="text/css" />
</head>

<body>


    <div class='container'>
        <div id="barra_user" class="row test">
            <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px; align=right" > 
                <li class="pull-right perfil">
                    <a href="{base_url}user/logout"><strong>SALIR</strong></a>
                </li>
                <li class="pull-right perfil">
                    <a href="javascript:history.back()">VOLVER /</a>
                </li>
            </ul>
        </div>
        <!-- ============= Barra Ministerio  -->

        <div class='row'>
            {logobar}
        </div>
        <!-- ============= Formulario  -->

        <div class='row'>
            <div class='col-md-12'>
                
                <h1>IVA – Cancelación trimestral</h1>
                <h2>Consulta de Estado por Nro de C.U.I.T.</h2>
                <h3>QUEUE</h3>                          
                <!-- LIST -->
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>CUIT</th>
                            <th>Razón Social/Nombre</th>
                            <th>Forma jurídica</th>
                            <th>Marca</th>
                            <th>Diferido?</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        {queue_list}
                    <tr>
                        <td><a href="{module_url}consultas/source/{cuit}" alt='source {cuit}' target='_blank'><i class='fa fa-plus'></i></a></td>
                        <td>{cuit}</td>
                        <td>{denominacion}
                        <br>
                            {result actividad} - {result actividad_texto}
                        </td>
                        <td>{formajuridica}</td>
                        <td>{flags}</td>
                        <td>{diferido}</td>
                    </tr>
                        {/queue_list}
                    </tbody>
                </table>
                            

                            </li>
                </div>

        </div>
    </div>
    <!-- JS Global -->
    <script>
        //-----declare global vars
        var base_url = '{base_url}';
    </script>

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{base_url}jscript/jquery/jquery.min.js"></script>
    <script src="{base_url}dashboard/assets/bootstrap-wysihtml5/js/bootstrap.min.js"></script>
    <script src="{base_url}jscript/jquery/plugins/jquery-validation-1.15.0/jquery.validate.min.js"></script>   
    <script src="{base_url}afip/assets/jscript/form_consultas_cuit.js"></script>
    <!--CALENDAR -->
    <script src='{base_url}jscript/jquery/ui/jquery-ui-1.10.2.custom/jquery-ui-1.10.2.custom.min.js'></script>
    <script src='{base_url}dashboard/assets/bootstrap-wysihtml5/js/AdminLTE/app.js'></script>
    <script src='{base_url}jscript/jquery/plugins/Form/jquery.form.min.js'></script>
  

</body>

</html>
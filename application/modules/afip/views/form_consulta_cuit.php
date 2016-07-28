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

                <!-- MSGs -->
                <div id='ready' style="display:none;margin-top:20px" class='cuit_all'>
                    <div class="alert alert-success" role="alert">         
                    La C.U.I.T. <span id="s_cuit"></span> ya fue informada a AFIP.                    
                    </div>                    
                </div>
              

                <div id='waiting' style="display:none;margin-top:20px" class='cuit_all'>
                    <div class="alert alert-info" role="alert">         
                    La C.U.I.T. <span id="w_cuit"></span> se encuentra en espera.                    
                    </div>                    
                </div>

                

                <div id='revision' style="display:none;margin-top:20px" class='cuit_all'>
                    <div class="alert alert-warning" role="alert">         
                    La C.U.I.T. <span id="r_cuit"></span> se encuentra en Revisión.                    
                    </div>                    
                </div>

                <div id='msg_error' style="display:none;margin-top:20px" class='cuit_all'>
                    <div class="alert alert-danger" role="alert">
                       La C.U.I.T. <span id="e_cuit"></span> no está pendiente de Revisión ni en Espera
                    </div>
                </div>

            <form method="post" class="well">
                <div class="row">
                    <div class="col-md-6"> 
                        <div class="form-group">
                            <label>Ingrese la C.U.I.T. a consultar</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                           <input type="text" class="form-control" id="cuit" name="cuit" placeholder="XXXXXXXXXXXX">
                        </div>
                        
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <button name="submit" class="btn btn-block btn-primary hide_offline" type="submit" id="bt_save_"><i class="icon-save"></i>Enviar</button>  
                    </div>
                </div>
            </form>
            
               <hr>

                <div class="well">
                    <h2>Referencias:</h2>
                    <ul>
                        <li><span class="span_alert alert-success">READY</span> LA CUIT ya fue informada a AFIP.</li>
                        <li><span class="span_alert alert-danger">ERROR</span> LA CUIT no está pendiente de "Revisión" ni en "Espera".</li>
                        <li><span class="span_alert alert-info">ESPERA</span> LA CUIT no recibio aún toda la informacion necesaria para su evaluación.</li>
                        <li><span class="span_alert alert-warning">REVISION</span> LA CUIT ya cuenta con la información necesaria para su revisión y espera su confirmación.</li>
                    </ul>
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
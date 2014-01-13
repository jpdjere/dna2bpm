<!DOCTYPE html>
<html>
    <head>
        <title>DNA&sup2; | SGR | {sgr_nombre}</title>
        <meta charset="UTF-8" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="{base_url}jscript/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="{base_url}jscript/bootstrap/css/bootstrap-responsive.min.css" />
        <!--<link rel="stylesheet" href="{base_url}jscript/fontawesome/css/font-awesome.min.css" />-->
        <link rel="stylesheet" href="{module_url}assets/css/font-awesome-4.0.3/css/font-awesome.min.css" />
        <link rel="stylesheet" href="{module_url}assets/jscript/jquery-ui-1.10.2.custom/css/smoothness/jquery-ui-1.10.2.custom.min.css" />	
        <link rel="stylesheet" href="{module_url}assets/css/extra-icons.css" />	
        <link rel="stylesheet" href="{module_url}assets/jscript/fullcalendar/fullcalendar.css" />
        <link rel="stylesheet" href="{module_url}assets/jscript/datepicker/css/datepicker.css" />
        <link rel="stylesheet" href="{module_url}assets/css/sgr.css" />
    </head>
    <body>

        <div class="container" > 
            <div class="navbar navbar-inverse navbar-static-top ">
                <div id="header">
                    <div id="header-dna"></div>
                    <div id="header-logos"></div>
                </div>
                <div class="row-fluid"> 
                    <h2><i class="fa fa-bars"> {sgr_nombre} | C.U.I.T.: {sgr_cuit}</i></h2>
                    <h3>[Anexo 06] SGR {anexo_title} Importado por: [ADMINISTRADOR]</h3>
                    <h4>Archivo Procesado: {parameter}</h4>                    
                    <div class="alert alert-info">
                        <ul><li class="pull-right perfil">
                                Información correspondiente al período 11/2013 | <a href="javascript:window.print();"><i class="fa fa-print" alt="Imprimir"></i></a> | <a href='javascript:window.close();'>Cerrar Anexo</a><br>
                            </li>
                        </ul>
                    </div>
                </div>
                {show_table}
            </div>
        </div>
    </body>
</html>


<!DOCTYPE html>
<html>
    <head>
        <title>DNA&sup2; | SGR | {sgr_nombre}</title>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="{base_url}jscript/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="{base_url}jscript/bootstrap/css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="{module_url}assets/css/font-awesome-4.0.3/css/font-awesome.min.css" />
        <link rel="stylesheet" href="{module_url}assets/css/sgr.css" />
        <link rel="stylesheet" href="{module_url}assets/css/print.css" />
    </head>
    <body>
        <div class="container" > 
            <div class="navbar navbar-inverse navbar-static-top ">
                <div id="header">
                    <div id="header-dna"></div>
                    <div id="header-logos"></div>
                </div>
                <div class="row-fluid"> 
                    <h2><i class="fa fa-bars"></i> {sgr_nombre} | C.U.I.T.: <span class="text-info">{sgr_cuit}</span></h2>
                    <p>[Anexo {anexo_short}] SGR {anexo_title} - Importado por: [{user_print}]<br/>
                        <small>Archivo Procesado: {parameter}</small>                                  
                        <span class="pull-right perfil">
                            Información correspondiente al período {print_period} | <a href="javascript:window.print();"><i class="fa fa-print" alt="Imprimir"></i></a> | <a href='javascript:window.close();'>CERRAR</a><br>
                        </span></p>
                </div>
                {show_table}
            </div>
        </div>
    </body>
</html>


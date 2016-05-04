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
                <div class="header_institucional">
                  <img src="{base_url}dashboard/assets/img/logo_presidencia.png" class="presidencia_logo">
                  <img src="{base_url}dashboard/assets/img/logo_secretaria.png" class="secretaria_logo">
                </div>
                <div class="row-fluid" align="center">                    

                    <h2>{sgr_nombre}</h2>
                    <h4>Declaración Jurada sobre la Presentación de los Anexos 12, 13, 14, 15 y 16</h4>
                    <h3>PER&Iacute;ODO: {print_period}</h3>
                </div>

                <div class="row-fluid">
                    <div id="meta_div_2">
                        <form  method="post" class="well" id="form">
                            <div  class="row-fluid " >
                                <div class="span6">                        

                                    <label>COMISIONES DEVENGADAS EN EL PERÍODO</label>
                                    <input type="text" name="comisions" placeholder="Ingrese la cantidad" class="input-block-level">  
                                    <h5>POR GARANTIAS OTORGADAS</h5>
                                </div>

                                <div class="span6">
                                    <label>OBSERVACIONES</label>
                                    <textarea name="observations" placeholder="..." class="input-block-level" ></textarea>                        
                                </div>


                            </div>
                            <div  class="row-fluid">
                                <div class="span12">
                                    <input type="hidden" name="period" value="{print_period}" />
                                    <input type="hidden" name="anexo" value="{anexo}" />
                                    <button name="submit_period" class="btn btn-block btn-primary hide_offline" type="submit" id="bt_save_{sgr_period}"><i class="fa fa-cog"></i> Generar Anexo 17</button>  
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </body>
</html>


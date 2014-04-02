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
        <!--/ Custom CSS -->
        {css}

    </head>
    <body>
        <div class="row-fluid test" id="barra_user" > 
            <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
                 <li class="pull-right perfil"><a  href="{base_url}user/logout">
                         SALIR</a></li>
                <li class="pull-right perfil">
                  <i class="{rol_icono}"></i> <strong> {sgr_nombre} </strong> <span class="">  {username}</span> |
                </li>        
                <!--<li class="pull-right perfil"><a  href="../dna2/" target="_blank"><i class="fa fa-link"></i> Acceso Versión Anterior | </a></li>-->
               
            </ul>
        </div>
        
        <div id="header">
            <div id="header-dna"></div>
            <div id="header-logos"></div>
        </div>
        
        
        <!--/ NAVIGATION -->
        <div class="navbar">
            <div class="navbar-inner barra_sgr">
                <div class="container">
                    
                    <a class="brand" href="{module_url}">SOCIEDADES DE GARANTIAS RECIPROCAS</a>
                   

                    <div class="nav-collapse collapse">
                        <ul class="nav pull-right inline">
                            {if sgr_period}
                            <li><a href="{base_url}sgr/unset_period" id="icon-calendar"><i class="icon-calendar"></i> Período: <span id="sgr_period"> {sgr_period}</span></a></li>    
                            {/if}
                            <li class="dropdown" id="menu-messages">
                                <a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle">
                                    <i class="fa fa-file-text">
                                    </i> <span class="text"> Anexos:</span> <span class=""> {anexo_title_cap} </span> <b class="caret">
                                    </b>
                                </a>
                                <ul class="dropdown-menu">
                                    {anexo_list}
                                </ul>
                            </li>
                            <li></li>                            
                        </ul>
                    </div>
                </div>



            </div>
        </div>
        
        <!-- CONTAINER -->
        {content}
        <!-- CONTAINER -->

        <script src="{module_url}assets/jscript/jquery.min.js"></script>
        <script src="{base_url}jscript/bootstrap/js/bootstrap.min.js"></script>
        <script src="{module_url}assets/jscript/bootbox.min.js"></script>
        <script src="{module_url}assets/jscript/datepicker/js/bootstrap-datepicker.js"></script>
        <script src="{module_url}assets/jscript/modernizr.custom.22198.js"></script>
        <!-- Custom JS -->
        <script type="text/javascript">
            //-----declare global vars
            var globals = {inline_js};

            // La clase offline es agregada por el fallback del manifiesto
            var offline = $('.offline').length;

            $(document).ready(function() {
                if (offline) {
                    $('#status').css('color', '#f00');

                } else {
                    $('#status').css('color', '#059B28');
                }
            });



        </script>
        {js}
    </body>
</html>

<!DOCTYPE html>
<html lang="es" manifest="{base_url}genias/manifest/offline.appcache">

    <head>
        <title>DNA&sup2; Admin</title>
        <meta charset="UTF-8" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="{base_url}jscript/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="{base_url}jscript/bootstrap/css/bootstrap-responsive.min.css" />


        <link rel="stylesheet" href="{base_url}jscript/fontawesome/css/font-awesome.min.css" />
        <link rel="stylesheet" href="{module_url}assets/jscript/jquery-ui-1.10.2.custom/css/smoothness/jquery-ui-1.10.2.custom.min.css" />	
        <link rel="stylesheet" href="{module_url}assets/css/extra-icons.css" />	
        <link rel="stylesheet" href="{module_url}assets/jscript/fullcalendar/fullcalendar.css" />
        <link rel="stylesheet" href="{module_url}assets/jscript/datepicker/css/datepicker.css" />

        <link rel="stylesheet" href="{module_url}assets/css/genias.css" />


        <!--/ Custom CSS -->
        {css}

    </head>
    <body>
        <!--/ NAVIGATION -->
        <div class="navbar navbar-inverse navbar-static-top ">
            <div class="navbar-inner barra_{rol}">
                <div class="container">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="brand" href="#">GenIA</a>

                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li><a href="{module_url}">Inicio</a></li>
                            <li><a href="{module_url}tasks">Tareas</a></li>
                            <li><a href="{module_url}scheduler">Agenda</a></li>
                            <li><a href="{module_url}form_empresas_alt"><i class='icon-plus'></i> Visita</a></li>  
                            <li><a href="{module_url}listado_empresas">Empresas</a></li>  
                            <li><a href="{module_url}map">Mapa</a></li>      

                        </ul>
                        <ul class="nav pull-right inline"> 


                            <li class="dropdown" id="menu-messages">
                                <a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle">
                                    <i class="icon icon-coment">
                                    </i> <span class="text">{lang messages}</span> <span class="label label-important">{inbox_count}</span> <b class="caret">
                                    </b>
                                </a>
                                <ul class="dropdown-menu">
<!--                                    <li>
                                        <a class="sAdd" title="" href="{module_url}inbox/new_msg">{lang new_message}</a>
                                    </li>-->
                                    <li>
                                        <a class="sInbox" title="" href="{module_url}inbox/">{lang inbox}</a>
                                    </li>
                                    <li>
                                        <a class="sOutbox" title="" href="#">{lang outbox}</a>
                                    </li>
                                    <li>
                                        <a class="sTrash" title="" href="#">{lang trash}</a>
                                    </li>
                                </ul>
                            </li>
                            <li><img src="{gravatar}"  title="{username}"  style="height:40px;back"/></li>
                            <li><a   href="{base_url}user/logout"><i class="icon-off"></i> Salir</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
 
        <!-- CONTAINER -->
        {content}
        <!-- CONTAINER -->

        <script src="{module_url}assets/jscript/jquery.min.js"></script>
<!--        <script src="{module_url}assets/jscript/jquery-ui-1.10.2.custom/js/jquery-ui-1.10.2.custom.min.js"></script>-->
        <script src="{base_url}jscript/bootstrap/js/bootstrap.min.js"></script>
        <script src="{module_url}assets/jscript/datepicker/js/bootstrap-datepicker.js"></script>
        <script src="{module_url}assets/jscript/fullcalendar/fullcalendar.min.js"></script>

        <script src="{module_url}assets/jscript/modernizr.custom.22198.js"></script>
        <!-- Custom JS -->
        <script type="text/javascript">
            //-----declare global vars
            var globals={inline_js};
        </script>
        {js}


    </body>
</html>

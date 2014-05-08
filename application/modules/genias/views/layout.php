<?php
header('Cache-Control: no-cache,max-age=0,must-revalidate');
$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time()-3600 ) . " GMT";
header($ExpStr);
?>
<!DOCTYPE html>
<html>
<head>
        <title>DNA&sup2; Admin </title>
        <meta charset="UTF-8" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="{base_url}jscript/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="{base_url}jscript/bootstrap/css/bootstrap-responsive.min.css" />


        <link rel="stylesheet" href="{module_url}assets/css/font-awesome-4.0.3/css/font-awesome.min.css" />
        <link rel="stylesheet" href="{module_url}assets/jscript/jquery-ui-1.10.2.custom/css/smoothness/jquery-ui-1.10.2.custom.min.css" />	
        <link rel="stylesheet" href="{module_url}assets/css/extra-icons.css" />	
        <link rel="stylesheet" href="{module_url}assets/jscript/fullcalendar/fullcalendar.css" />
        <link rel="stylesheet" href="{module_url}assets/jscript/datepicker/css/datepicker.css" />
        <link rel="stylesheet" href="{module_url}assets/css/genias.css" />


        <!--/ Custom CSS -->
        {css}

    </head>
    <body class="{is_offline}">
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
                            <li  class="dropdown" id="menu-visitas">
                                <a href="#" data-toggle="dropdown" data-target="#menu-visitas" class="dropdown-toggle">Visita <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{module_url}form_empresas_alt"><i class='fa fa-plus'></i> Empresas</a></li>
                                    <li><a href="{module_url}form_instituciones"><i class='fa fa-plus'></i> Instituciones</a></li>
                                </ul>
                            </li>  
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
                            <li><a   href="{base_url}user/logout"><i class="fa fa-power-off"></i> Salir</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
 
        <!-- CONTAINER -->
        {content}
        
   
       <!-- Button to trigger modal -->
    <a href="#myModal" role="button" class="btn" data-toggle="modal">Launch demo modal</a>
     
	    <!-- MODAL -->
	    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	    <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h3>Modal header</h3>
	    </div>
	    <div class="modal-body">
	    <p>One fine body…</p>
	    </div>

	    </div>
        
        <!-- CONTAINER -->

        <script src="{module_url}assets/jscript/jquery.min.js"></script>
        <script src="{base_url}jscript/bootstrap/js/bootstrap.min.js"></script>
        <script src="{module_url}assets/jscript/bootbox.min.js"></script>
        <script src="{module_url}assets/jscript/datepicker/js/bootstrap-datepicker.js"></script>
        <script src="{module_url}assets/jscript/modernizr.custom.22198.js"></script>
        <!-- Custom JS -->
        <script type="text/javascript">
            //-----declare global vars
            var globals={inline_js};
            
            // La clase offline es agregada por el fallback del manifiesto
            var offline =$('.offline').length;
            
            $( document ).ready(function() {
                if(offline){
                    $('#status').html('OFFLINE').css('color','#f00');
                    
                }else{
                     $('#status').html('ONLINE').css('color','#059B28');
                }
            });
            


        </script>
        {js}


    </body>
</html>

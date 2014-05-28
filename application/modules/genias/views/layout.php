<?php
header('Cache-Control: no-cache,max-age=0,must-revalidate');
$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time()-3600 ) . " GMT";
header($ExpStr);
?>
<!DOCTYPE html>
<html>
<head>
<title>DNA&sup2; Admin</title>
<meta charset="UTF-8" />

<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="{module_url}assets/jscript/bootstrap/css/bootstrap.min.css" />


<link rel="stylesheet"	href="{module_url}assets/css/font-awesome-4.0.3/css/font-awesome.min.css" />
<link rel="stylesheet"	href="{module_url}assets/jscript/jquery-ui-1.10.2.custom/css/smoothness/jquery-ui-1.10.2.custom.min.css" />
<link rel="stylesheet" href="{module_url}assets/css/extra-icons.css" />
<link rel="stylesheet"	href="{module_url}assets/jscript/fullcalendar/fullcalendar.css" />
<link rel="stylesheet"	href="{module_url}assets/jscript/datepicker/css/datepicker.css" />
<link rel="stylesheet" href="{module_url}assets/css/genias.css" />


<!--/ Custom CSS -->
{css}

</head>
<body class="{is_offline}">
	<!--/ NAVIGATION -->
	<nav class="navbar navbar-default" role="navigation">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse"
					data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span> <span
						class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#"><img src="{gravatar}" title="{username}" style="height: 40px;margin-top:-10px" /></a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse"
				id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
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
			<!--  ========== RIGHT ========== -->
				<ul class="nav navbar-nav navbar-right">
					
					<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
					<span class="text">{lang messages} </span><span class="label label-info"><i class="fa fa-comment"></i> {inbox_count}</span> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a class="sInbox" title="" href="{module_url}inbox/">{lang inbox}</a></li>
							<li><a class="sOutbox" title="" href="#">{lang outbox}</a></li>
							<li><a class="sTrash" title="" href="#">{lang trash}</a></li>
						</ul></li>
											
					<li><a href="{base_url}user/logout"><i class="fa fa-power-off"></i> Salir</a></li>
					<li class="hidden-xs"><a id="status"><i class="fa fa-globe"></i></a></li>
				</ul>
				</li>
				</ul>
			</div>
			<!-- /.navbar-collapse -->
		</div>
		<!-- /.container-fluid -->
	</nav>


	<!-- ======================-->


	<!-- CONTAINER -->
	{content}


	<!-- CONTAINER -->

<script src="{module_url}assets/jscript/jquery.min.js"></script>

<script src="{module_url}assets/jscript/bootstrap/js/bootstrap.min.js"></script>
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
                    $('#status').css('color','#cccccc').attr('title','offline');
                 
                }else{
                     $('#status').css('color','#059B28').attr('title','online');;
                }
            });
            
            $('.mypopover').popover();

        </script>
	{js}



</body>
</html>

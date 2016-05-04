<!-- ==== Contenido ==== -->
<div class="container" > 

    <div class="row-fluid test" id="barra_user" > 
        <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
            <li class="pull-right perfil"><a  href="javascript:window.close()">
                    CERRAR</a></li>
            <li class="pull-right perfil">
                <i class="{rol_icono}"></i> <strong> {sgr_nombre} </strong> <span class="">  {username}</span> |
            </li>        
            <!--<li class="pull-right perfil"><a  href="../dna2/" target="_blank"><i class="fa fa-link"></i> Acceso Versión Anterior | </a></li>-->

        </ul>
    </div>

   <div class="header_institucional">
      <img src="{base_url}dashboard/assets/img/logo_presidencia.png" class="presidencia_logo">
      <img src="{base_url}dashboard/assets/img/logo_secretaria.png" class="secretaria_logo">
    </div>


<!--/ NAVIGATION -->
    
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header ">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <h2><i class="fa fa-copy"></i> SGR REPORTES</h2>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
<!-- Derecha -->
      <ul class="nav navbar-nav navbar-right">

        <li class="dropdown">
             <a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle">
                <i class="fa fa-file-text"> </i> <span class="text"> Anexos:</span> <span class=""> {anexo_title_cap} </span> <b class="caret"></b>
            </a> 
            <ul class="dropdown-menu">
                {anexo_list}
            </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>


<!-- FORMULARIO -->


    <div class="row-fluid">
        {form_template}
    </div>



</div>
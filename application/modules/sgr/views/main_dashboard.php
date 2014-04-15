<!-- ==== Contenido ==== -->
<div class="container" > 

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


<!-- ======= ANEXOS ======= -->

<div class="panel panel-default">
    <div class="panel-heading">
    <h3 class="panel-title">Anexos <a href="#" class="pull-right togglepanel"><i class="fa fa-chevron-up"></i></a></h3>
</div>
  <div class="panel-body">
    {anexo_list}
  </div>
</div>



</div>
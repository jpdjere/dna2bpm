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


<!-- ======= ANEXOS - Grupo 58 Only ======= -->


<div class="panel-group" id="accordion">
<!-- =======Grupo 58 Only ======= -->
{if {is_sgr_sociedades}==1}
<div class="panel panel-default">
  <!--  Panel Anexos -->
    <div class="panel-heading">
      <h4 class="panel-title"><i class="fa fa-table"></i> Anexos
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1" class="pull-right">
           <i class="fa fa-chevron-up"></i></a>
        </a>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse in">
      <div class="panel-body">
        {anexo_list}
      </div>
    </div>
 </div>
 <!--  Panel Herramientas -->
 <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title"><i class="fa fa-wrench"></i> Herramientas
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" class="pull-right">
          <i class="fa fa-chevron-down"></i>
        </a>
      </h4>
    </div>
    <div id="collapse2" class="panel-collapse collapse ">
      <div class="panel-body">
        xxxxxxxx Próximamente xxxxxxxx
      </div>
    </div>
 </div>
 {/if}
 <!--  Panel Reportes -->
 <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title"><i class="fa fa-copy"></i> Reportes
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3" class="pull-right">
          <i class="fa fa-chevron-down"></i>
        </a>
      </h4>
    </div>
    <div id="collapse3" class="panel-collapse collapse ">
      <div class="panel-body">
        xxxxxxxx Próximamente xxxxxxxx
      </div>
    </div>
 </div>
</div>
  
  



</div>
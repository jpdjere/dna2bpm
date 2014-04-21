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
<!-- ======= ANEXOS - Grupo 58 Only ======= -->
{if {is_sgr_sociedades}==1}
<div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">Anexos
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
 {/if}
 <!--  Panel Listados -->
 <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">Listados
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" class="pull-right">
          <i class="fa fa-chevron-up"></i>
        </a>
      </h4>
    </div>
    <div id="collapse2" class="panel-collapse collapse in">
      <div class="panel-body">
        xxxxxxxx Proximamente xxxxxxxx
      </div>
    </div>
 </div>
</div>
  
  



</div>
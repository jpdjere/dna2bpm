<div class="row-fluid test" id="barra_user" > 
    <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
          
        <li class="pull-right perfil">
            SGR: {sgr_nombre}  <span id="status"> <i class="{rol_icono}"></i> {username} [Grupo: {rol}]</span>
        </li>        
        <li class="pull-right perfil"><a  href="../dna2/" target="_blank"><i class="fa fa-link"></i> Acceso Versión Anterior | </a></li>
    </ul>
</div>



<!-- ==== Contenido ==== -->
<div class="container" > 
    <h2><i class="fa fa-bars"></i> {anexo_short} {anexo_title_cap}</h2>  
    <div class="row-fluid">       
    </div>


    {if processed_list}
    <div class="well">
        <!-- PROCESSED ANEXOS-->
        <div id="show_anexos">                   

            <!-- TABS -->
            <ul class="nav nav-tabs" id="dashboard_tab1">       
                {processed_tab}
            </ul>
            <div class="tab-content perfil">
                {processed_list}
            </div>
        </div>
    </div>
    {else}
    <div id="{_id}" class="alert alert-error">
        No hay periodo disponible para generar DD.JJ. 

    </div>
    {/if}    
</div>
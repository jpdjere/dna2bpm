
<!-- ==== Contenido ==== -->
<div class="container" > 
    
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

     <h2><i class="fa fa-bars"></i> Información sobre la importación de {anexo_title_cap}</h2>

    <div class="alert alert-success" id="{_id}">       
            
        <h2>El Anexo se proceso correctamente.</h2>
        <h3>Periodo informado: {sgr_period}</h3>
        <p> </p>
        <hr>
        {if message_header}
            <ol>{message_header}</ol>
        {/if}

        {if message}
            <ol>{message}{print_file}</ol>
        {/if}
        <hr>
        <p><i class="fa fa-download"></i> <a href=>Descargar Modelo</a> | <i class="fa fa-download"></i> <a href=>Descargar Manual</a>        
        | <i class="fa fa-backward"></i><a href="../../sgr/"> Volver</a> | <i class="fa fa-print"></i> <a href="javascript:window.print()">Imprimir Pantalla</a></p>
    </div>
</div>
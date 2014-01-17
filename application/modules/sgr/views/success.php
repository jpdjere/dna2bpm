<div class="row-fluid test" id="barra_user" > 
    <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
        <button type="button" class="btn hide_offline" data-toggle="collapse" data-target="#file_div">
            <i class="fa fa-plus"></i>  Seleccionar Archivos a Procesar
        </button> 
            
        <li class="pull-right perfil">
            SGR: {sgr_nombre}  <span id="status"> <i class="{rol_icono}"></i> {username} [Grupo: {rol}]</span>
        </li>        
        <li class="pull-right perfil"><a  href="../dna2/" target="_blank"><i class="fa fa-link"></i> Versión Anterior | </a></li>
    </ul>
</div>
<!-- ==== Contenido ==== -->
<div class="container" > 
    <h1 class="success">Información sobre la importación del Anexo</h1>
    <div class="alert alert-success" id="{_id}">       
        <i class="fa fa-backward"></i><a href="../../sgr/"> Volver</a> | <i class="fa fa-print"></i> <a href="javascript:window.print()">Imprimir pantalla</a>   </p> 
        <h3>El Anexo se proceso correctamente.</h3>
        <h4>Periodo informado: {sgr_period}</h4>
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

<!-- ==== Contenido ==== -->

<div class="container" > 
    <div class="row test" id="barra_user" > 
        <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
            <li class="pull-right perfil"><a  href="../../sgr/">
                    VOLVER</a></li>
            <li class="pull-right perfil">
                <i class="{rol_icono}"></i> <strong> {sgr_nombre} </strong> <span class="">  {username}</span> |
            </li>        
            
        </ul>
    </div>

    <div id="header">
        <div id="header-dna"></div>
        <div id="header-logos"></div>
    </div>

     <h2><i class="fa fa-bars"></i> Errores en la importación de {anexo_title_cap} </h2>

    
    <div class="alert alert-danger" id="{_id}">       
          </p> 
        <h2>Verifique la lista de errores.</h2>
        {if message_header}
            <ol>{message_header}</ol>
        {/if}

        {if message}
            <ol>{message}</ol>
        {/if}
        <hr>
        <p>
            <i class="fa fa-download"></i> <a href="{module_url}assets/download/modelos.zip" target="_self">Descargar Modelos de Importación</a> / 
            <i class="fa fa-download"></i> <a href="{module_url}assets/download/documentacion.zip" target="_self">Descargar Documentación</a> / 
            <i class="fa fa-backward"></i> <a href="{module_url}">Volver</a> / 
            <i class="fa fa-print"></i> <a href="javascript:window.print()">Imprimir</a>
        </p>
    </div>
</div>
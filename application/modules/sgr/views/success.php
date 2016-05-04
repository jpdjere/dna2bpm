
<!-- ==== Contenido ==== -->
<div class="container" > 

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

        <div class="header_institucional">
          <img src="{base_url}dashboard/assets/img/logo_presidencia.png" class="presidencia_logo">
          <img src="{base_url}dashboard/assets/img/logo_secretaria.png" class="secretaria_logo">
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
            <p>
                <i class="fa fa-download"></i> <a href="{module_url}assets/download/modelos.zip" target="_self">Descargar Modelos de Importación</a> / 
                <i class="fa fa-download"></i> <a href="{module_url}assets/download/documentacion.zip" target="_self">Descargar Documentación</a> / 
                <i class="fa fa-backward"></i> <a href="{module_url}">Volver</a> / 
                <i class="fa fa-print"></i> <a href="javascript:window.print()">Imprimir</a>
            </p>
        </div>
    </div>
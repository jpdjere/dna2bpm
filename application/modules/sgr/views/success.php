<div class="row-fluid test" id="barra_user" > 
    <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
        
        <li class="pull-right perfil">
            <span id="status"></span>{sgr_nombre}
            <i class="{rol_icono}"></i>  <a   href="{base_url}user/logout"> {username}</a> [{rol}]
        </li>
    </ul>
</div>
<!-- ==== Contenido ==== -->
<div class="container" > 
    <div class="alert alert-success" id="{_id}">       
        <h3>Detalle</h3>    
        
        {if message_header}
            <ol>{message_header}</ol>
        {/if}

        {if message}
            <ol>{message}</ol>
        {/if}
        <a href="../../sgr/">VOLVER</a> | <i class="icon-print"></i> <a href="javascript:window.print()">Imprimir</a>
    </div>
</div>
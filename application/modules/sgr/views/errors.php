<div class="row-fluid test" id="barra_user" > 
    <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
        <button type="button" class="btn hide_offline" data-toggle="collapse" data-target="#file_div">
            <i class="icon-plus"></i>  Seleccionar Archivos a Procesar
        </button> 
        {if sgr_period}
        <button type="button" id="no_movement" class="no_movement" value="{sgr_period}">
            <i class="icon-plus"></i>  Asociar el periodo {sgr_period} a "Sin Movimientos"
        </button>
        {/if}
        <li class="pull-right perfil">
            <span id="status"></span>{sgr_nombre}
            <i class="{rol_icono}"></i>  <a   href="{base_url}user/logout"> {username}</a> [{rol}]
        </li>
    </ul>
</div>
<!-- ==== Contenido ==== -->
<div class="container" > 
    <div class="alert alert-error" id="{_id}">       
        <h3>Detalle de Errores</h3>    

        <a href="../../sgr/">VOLVER</a> | <i class="icon-print"></i> <a href="javascript:window.print()">Imprimir</a>    
        <h5>Verifique la lista de errores.</h5>
        {if message_header}
            <ol>{message_header}</ol>
        {/if}

        {if message}
            <ol>{message}</ol>
        {/if}
        <a href="../../sgr/">VOLVER</a> | <i class="icon-print"></i> <a href="javascript:window.print()">Imprimir</a>
    </div>
</div>
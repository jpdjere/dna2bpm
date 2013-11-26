<div class="row-fluid test" id="barra_user" > 
    <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >

        <li class="pull-right perfil">
            <span id="status"></span>
            <a title="{usermail}">{username}</a> <i class="icon-angle-right"></i> <i class="{rol_icono}"></i> {rol}
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
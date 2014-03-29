<div class="row-fluid test" id="barra_user" > 
    <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >

        <button type="button" class="btn hide_offline" data-toggle="collapse" data-target="#file_div">
            <i class="fa fa-plus"></i>  Seleccionar Archivos a Procesar
        </button> 
        {if sgr_period}        
        <button type="button" id="no_movement" class="no_movement btn btn-info" value="{sgr_period}">
            <i class="fa fa-spinner fa-spin"></i>  Asociar el periodo {sgr_period} a "Sin Movimientos"
        </button>
        {/if}        
        <li class="pull-right perfil">
            SGR: {sgr_nombre}  <span id="status"> <i class="{rol_icono}"></i> {username} [Grupo: {rol}]</span>
        </li>        
        <li class="pull-right perfil"><a  href="../dna2/" target="_blank"><i class="fa fa-link"></i> Acceso Versión Anterior | </a></li>
    </ul>
</div>


<!-- MESSAGES -->
{if period_message}
<div class="alert alert-{success}" id="{success}">   
    {period_message}
</div>
{else}
{if message}
<div class="alert alert-{success}" id="{success}">   
    {message}
</div>
{/if}
{/if}


<!-- ==== Contenido ==== -->
<div class="container" > 
    <h2><i class="fa fa-bars"></i> {anexo_short} {anexo_title_cap}</h2>  

    <!-- RECTIFICATION ALERT-->
    {rectify_message_template}



    <div class="row-fluid">
        {if $files_list}
        {else}
        <!-- FILE UPLOAD -->
        {upload_form_template}
        {/if}
    </div>


    <!-- FORM PERIOD--> 
    {form_template}
    <!-- UPLOADED FILES LIST-->
    {files_list}   

    <!-- PENDING LIST -->
    {if pending_list}
    <div class="well">
        <!-- PENDING ANEXOS-->
        <div id="show_anexos" class="perfil">
            
            <ul>
                {pending_list}
            </ul>
            <p>Una vez que el archivo se importó correctamente debe aparecer automáticamente la pantalla para importar el ANEXO 6.1 – RELACIONES DE VINCULACIÓN. De forma que este se cargue obligatoriamente. De no cargarse, se cancelará toda la importación del ANEXO 6 – MOVIMIENTOS DE CAPITAL SOCIAL.</p>
            <p><a href="sgr/anexo_code/061">Continuar</a></p>
        </div>

    </div>
    <hr>
    {/if}        


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
        No hay Archivos Procesados para este anexo. 

    </div>
    {/if}

    {if rectified_list}
    <hr>
    <div class="well">
        <!-- RECTIFIED ANEXOS-->
        <div id="show_anexos"> 
            <!-- TABS -->
            <ul class="nav nav-tabs" id="dashboard_rec_tab1">       
                {rectified_tab}
            </ul>
            <div class="tab-content perfil">
                {rectified_list}
            </div>
        </div>
    </div>
    {/if}
</div>
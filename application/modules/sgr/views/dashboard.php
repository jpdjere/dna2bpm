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
        <li class="pull-right perfil"><a  href="../dna2/" target="_blank"><i class="fa fa-link"></i> Versión Anterior | </a></li>
    </ul>
</div>

{if message}
<div class="alert alert-{success}" id="{success}">   
    {message}
</div>
{/if}



<!-- ==== Contenido ==== -->
<div class="container" > 
    <h2><i class="fa fa-bars"></i> {anexo_title_cap}</h2>   
    <!-- RECTIFICATION ALERT-->
    {rectify_message}
   
    <div class="row-fluid">
        <!-- FILE UPLOAD -->
        <div id="file_div" class="collapse out no-transition">
            <form action="{module_url}" method="POST" enctype="multipart/form-data" class="well" />                   
            <input type="file" name="userfile" multiple="multiple" />
            <input type="hidden" name="sgr" value="{sgr_id_encode}" />
            <input type="hidden" name="anexo" value="{anexo}" />
            <input type="submit" name="submit" value="Upload" class="btn btn-success" />
            </form>

        </div> 
    </div>


    <div id="div_period">
        {form_template}
    </div>

    {if sgr_period} 
    <!-- -->
    {else}
    <!-- PERIOD -->
    {form_template}
    {/if}    
        
    <div id="show_anexos">        
        <div class="alert {resumen_class}" id="{_id}">                        
            <ol>
                {files_list}
            </ol>
        </div>
        {if processed_tab}
        <h3>ANEXOS PROCESADOS</h3>
        {/if}
        <!-- TABS -->
        <ul class="nav nav-tabs" id="dashboard_tab1">       
            {processed_tab}
        </ul>
        <div class="tab-content">
            {processed_list}
        </div>

    </div>
</div>

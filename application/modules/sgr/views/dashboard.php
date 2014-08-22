<!-- ==== Contenido ==== -->
<div class="container" > 

    <div class="row test" id="barra_user" > 
        <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
            
            
            <li class="pull-right perfil"><a  href="javascript:window.close()">CERRAR</a>
            
                
             <li class="pull-right perfil">
                <i class="{rol_icono}"></i> <strong>{if fre_session} FRE {else}  {sgr_nombre}{/if} </strong> <span class="">  {username}</span> |
            </li>
           
            {if fre_session}
            <li class="pull-right perfil" ><a  href="{base_url}sgr/exit_fre" class="alert alert-danger">CERRAR <strong> {sgr_nombre}</strong></a></li>
            {/if}
            
            <li class="pull-right perfil"><a  href="{base_url}sgr/dashboard"><strong>INICIO</strong></a></li>
            
        </ul>
    </div>

    <div id="header">
        <div id="header-dna"></div>
        <div id="header-logos"></div>
    </div>
    
<!-- ====== NAVIGATION ====== -->
    
      <nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header visible-xs">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Brand</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
<!-- Izquierda -->
      <ul class="nav navbar-nav">
        {if sgr_period}
        <li><a href="{base_url}sgr/unset_period" id="icon-calendar"><i class="fa fa-calendar"></i> Período <span id="sgr_period"> {sgr_period}</span></a></li>    
        {/if}
      </ul>
<!-- Derecha -->
      <ul class="nav navbar-nav navbar-right">

        <li class="dropdown">
             <a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle">
                <i class="fa fa-file-text"> </i> <span class="text"> Anexos:</span> <span class=""> {anexo_title_cap} </span> <b class="caret"></b>
            </a> 
            <ul class="dropdown-menu">
                {anexo_list}
            </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<!-- ====== Archivos a procesar ====== -->

<div class="row gap"  id="barra_user" > 

            <button type="button" class="btn hide_offline" data-toggle="collapse" data-target="#file_div">
                <i class="fa fa-plus"></i>  Seleccionar Archivos a Procesar
            </button> 
            {if sgr_period}        
            <button type="button" id="no_movement" class="no_movement btn btn-info" value="{sgr_period}">
                 Asociar el periodo {sgr_period} a "Sin Movimientos"
            </button>
            {/if} 

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
        <div id="show_anexos" class="alert alert-danger">

            <ul>
                {pending_list}
                <li>Para finalizar deberá importar el ANEXO 6.1 – RELACIONES DE VINCULACIÓN. De no cargarse, se cancelará toda la importación del ANEXO 6 – MOVIMIENTOS DE CAPITAL SOCIAL.</li>
                <li><a href="sgr/anexo_code/061">Continuar</a></li>
            </ul>

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
            <div class="tab-content ">
                {processed_list}
            </div>
        </div>
    </div>
    {else}
    <div id="{_id}" class="alert alert-danger">
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

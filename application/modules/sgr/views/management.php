<!-- ==== Contenido ==== -->
<div class="container" > 

    <div class="row test" id="barra_user" > 
        <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
            <li class="pull-right perfil"><a  href="javascript:window.close()">
                    CERRAR</a></li>
            {if menu_management}       
            <li class="pull-right perfil">
                <i class="{rol_icono}"></i> <strong> {sgr_nombre} </strong> <span class="">  {username}</span> |
            </li>              
            <li class="pull-right perfil"><a  href="{base_url}sgr/management/unset_sgr">INICIO</a></li>
            {else}            
            <li class="pull-right perfil">Admin <strong>{admin_username}</strong> / </li>
            <li class="pull-right perfil"><a  href="{base_url}sgr/management/unset_sgr">Terminar Session <strong> {sgr_nombre} </strong></a> </li>
            <li class="pull-right perfil"><a  href="{base_url}sgr/management/unset_sgr">INICIO</a></li>
            {/if}

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

    



    <div class="row-fluid">
        {if $files_list}
        {else}
        <!-- FILE UPLOAD -->
        {upload_form_template}
        {/if}
    </div>




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
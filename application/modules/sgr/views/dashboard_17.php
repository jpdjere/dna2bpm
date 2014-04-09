<!-- ==== Contenido ==== -->
<div class="container" > 

    <div class="row-fluid test" id="barra_user" > 
        <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
            <li class="pull-right perfil"><a  href="{base_url}user/logout">
                    SALIR</a></li>
            <li class="pull-right perfil">
                <i class="{rol_icono}"></i> <strong> {sgr_nombre} </strong> <span class="">  {username}</span> |
            </li>        
            <!--<li class="pull-right perfil"><a  href="../dna2/" target="_blank"><i class="fa fa-link"></i> Acceso Versión Anterior | </a></li>-->

        </ul>
    </div>

    <div id="header">
        <div id="header-dna"></div>
        <div id="header-logos"></div>
    </div>


    <!--/ NAVIGATION -->
    <div class="navbar">
        <div class="navbar-inner barra_sgr">
            <div class="container">

                <a class="brand" href="{module_url}">SOCIEDADES DE GARANTIAS RECIPROCAS</a>


                <div class="nav-collapse collapse">
                    <ul class="nav pull-right inline">
                        {if sgr_period}
                        <li><a href="{base_url}sgr/unset_period" id="icon-calendar"><i class="icon-calendar"></i> Período: <span id="sgr_period"> {sgr_period}</span></a></li>    
                        {/if}
                        <li class="dropdown" id="menu-messages">
                            <a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle">
                                <i class="fa fa-file-text">
                                </i> <span class="text"> Anexos:</span> <span class=""> {anexo_title_cap} </span> <b class="caret">
                                </b>
                            </a>
                            <ul class="dropdown-menu">
                                {anexo_list}
                            </ul>
                        </li>
                        <li></li>                            
                    </ul>
                </div>
            </div>



        </div>
    </div>
 
    <h2><i class="fa fa-bars"></i> {anexo_short} {anexo_title_cap}</h2>  
    <div class="row-fluid">       
    </div>


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
        No hay periodo disponible para generar DD.JJ. 

    </div>
    {/if}    
</div>
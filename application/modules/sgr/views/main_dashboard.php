<!-- ==== Contenido ==== -->
<div class="container" > 

    
    
    <div class="row test" id="barra_user" > 
        <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
            
            
            <li class="pull-right perfil"><a  href="{base_url}user/logout">SALIR</a>
            
                
             <li class="pull-right perfil">
                <i class="{rol_icono}"></i> <strong>{if fre_session} FRE {else}  {sgr_nombre}{/if} </strong> <span class="">  {username}</span> |
            </li>
           
            {if fre_session}
            <li class="pull-right perfil" ><a  href="{base_url}sgr/exit_fre" class="alert alert-danger">CERRAR <strong> {sgr_nombre}</strong></a></li>
            {/if}            
        </ul>
    </div>

    <div id="header">
        <div id="header-dna"></div>
        <div id="header-logos"></div>
    </div>


    <!-- ======= ANEXOS - Grupo 58 Only ======= -->

    <h1>DASHBOARD</h1>

    <div class="panel-group" id="accordion">
        <!-- =======Grupo 58 Only ======= -->
        {if {is_sgr_sociedades}==1}
        <div class="panel panel-default">
            <!--  Panel Anexos -->
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-table"></i> Anexos
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse1" class="pull-right">
                        <i class="fa fa-chevron-up"></i></a>
                    </a>
                </h4>
            </div>
            <div id="collapse1" class="panel-collapse collapse in">
                <div class="panel-body">
                    <li><a  href="{base_url_dna2}" target="_blank">Acceso <strong>Versión Anterior del Sistema</strong></a></li>
                    <hr>


                    <!--FRE -->
                    {if fre_list}
                    <h2>FONDOS DE RIESGO ESPEC&Iacute;FICOS </h2>
                    <div id="danger" class="alert alert-info">
                        {fre_list}                    
                    </div>
                    {/if}

                    <h2>SIPRIN 2014</h2>
                    {anexo_list}
                </div>
            </div>





        </div>

        <!--  Panel Herramientas -->
        <!--        <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><i class="fa fa-wrench"></i> Herramientas
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" class="pull-right">
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse2" class="panel-collapse collapse ">
                        <div class="panel-body">
                            xxxxxxxx Próximamente xxxxxxxx
                        </div>
                    </div>
                </div>-->
        {/if}
        <!--  Panel Reportes -->
        <!--        <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><i class="fa fa-copy"></i> Reportes
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse3" class="pull-right">
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse3" class="panel-collapse collapse ">
                        <div class="panel-body">
                            xxxxxxxx Próximamente xxxxxxxx
                        </div>
                    </div>
                </div>-->
    </div>





</div>
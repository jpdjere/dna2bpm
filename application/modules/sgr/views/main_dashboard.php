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

    <div class="header_institucional">
      <img src="{base_url}dashboard/assets/img/logo_presidencia.png" class="presidencia_logo">
      <img src="{base_url}dashboard/assets/img/logo_secretaria.png" class="secretaria_logo">
    </div>


    <!-- ======= ANEXOS - Grupo 58 Only ======= -->

    <h1>DASHBOARD</h1>

    <div class="panel-group" id="accordion">
        <!-- =======Grupo 58 Only ======= -->

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
                    {social_structure}
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




        <!--  Panel Reportes -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-copy"></i> Reportes
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse3" class="pull-right">
                        <i class="fa fa-chevron-down"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse3" class="panel-collapse collapse ">
                <div class="panel-body">
                    <li><a href="{module_url}reports/anexo_code/06" target="_blank">Reporte Anexo 06</a></li>
                    <li><a href="{module_url}reports/anexo_code/061" target="_blank">Reporte Anexo 06.1</a></li>
                    <li><a href="{module_url}reports/anexo_code/062" target="_blank">Reporte Anexo 06.2</a></li>
                    <li><a href="{module_url}reports/anexo_code/12" target="_blank">Reporte Anexo 12</a></li>
                    <li><a href="{module_url}reports/anexo_code/125" target="_blank">Reporte Anexo 12.5</a></li>
                    <li><a href="{module_url}reports/anexo_code/126" target="_blank">Reporte Anexo 12.6</a></li>
                    <li><a href="{module_url}reports/anexo_code/13" target="_blank">Reporte Anexo 13</a></li>
                    <li><a href="{module_url}reports/anexo_code/14" target="_blank">Reporte Anexo 14</a></li>
                    <li><a href="{module_url}reports/anexo_code/141" target="_blank">Reporte Anexo 14.1</a></li>
                    <li><a href="{module_url}reports/anexo_code/15" target="_blank">Reporte Anexo 15</a></li>
                    <li><a href="{module_url}reports/anexo_code/16" target="_blank">Reporte Anexo 16</a></li>
                    <li><a href="{module_url}reports/anexo_code/201" target="_blank">Reporte Anexo 20.1</a></li>
                    <li><a href="{module_url}reports/anexo_code/202" target="_blank">Reporte Anexo 20.2</a></li>
                </div>
            </div>
        </div>


        <!--  Panel Herramientas -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-cloud-download"></i> Descargas
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" class="pull-right">
                        <i class="fa fa-chevron-down"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse2" class="panel-collapse collapse ">
                <div class="panel-body">
                    <ul>
                        <li><i class="fa fa-download"></i> <a href="{module_url}assets/download/modelos.zip" target="_self">Descargar Modelos de Importación</a> </li>
                        <li><i class="fa fa-download"></i> <a href="{module_url}assets/download/documentacion.zip" target="_self">Descargar Documentación</a></li>

                    </ul>
                </div>
            </div>
        </div>

        <!--  Panel Central -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-book fa-fw"></i> Central
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse_central" class="pull-right">
                        <i class="fa fa-chevron-down"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse_central" class="panel-collapse collapse ">
                <div class="panel-body">
                    <li><a href="{module_url}central" target="_blank">Central Deudores</a></li>
                </div>
            </div>
        </div>
    </div>





</div>
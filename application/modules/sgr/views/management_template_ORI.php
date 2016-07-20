<!-- ==== Contenido ==== -->
<div class="container" > 

    <div class="row-fluid test" id="barra_user" > 
        <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
            <li class="pull-right perfil"><a  href="{base_url}user/logout">
                    SALIR</a></li>
            <li class="pull-right perfil">
                 <strong> {sgr_nombre} </strong> <span class="">  {username}</span> |
            </li>        
            <!--<li class="pull-right perfil"><a  href="../dna2/" target="_blank"><i class="fa fa-link"></i> Acceso Versión Anterior | </a></li>-->

        </ul>
    </div>

    <div class="header_institucional">
      <img src="{base_url}dashboard/assets/img/logo_presidencia.png" class="presidencia_logo">
      <img src="{base_url}dashboard/assets/img/logo_secretaria.png" class="secretaria_logo">
    </div>



    <h1>SGR MANAGER</h1>

    <!-- pic SGR-->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-briefcase"></i> SGRs
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse0" class="pull-right">
                    <i class="fa fa-chevron-down"></i>
                </a>
            </h4>
        </div>
        <div id="collapse0" class="panel-collapse collapse ">
            <form method="post" class="well" id="form" target="_blank" action="{module_url}management/Set_sgr/">

                <div class="row ">
                    <!--  ========================== row 4 . ========================== -->
                    <div class="col-md-12" >
                        <!--  Desde  -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Seleccione la SGR</label> 
                                <div class="input-group ">
                                    <select name="send_sgr" id="sgr"class="required form-control" > {sgr_options}</select>
                                </div>	
                            </div>
                        </div>
                        <!--  Hasta  -->

                    </div><!-- row4-->

                </div>



                <!--  ROW 3  -->
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="anexo" value="{anexo}" />
                        <button name="submit_period"
                                class="btn btn-block btn-primary hide_offline" type="submit"
                                id="bt_save_{sgr_period}">
                            <i class="fa fa-search"></i> SELECCIONAR
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!--  Panel Herramientas -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-wrench"></i> Herramientas
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" class="pull-right">
                    <i class="fa fa-chevron-down"></i>
                </a>
            </h4>
        </div>
        <div id="collapse2" class="panel-collapse collapse ">
            <div class="panel-body">
                 <li><a href="{module_url}tools" target="_blank">Periodos Informados por SGR</a></li>
                 <li><a href="{module_url}tools/fix_anexo141_balance_form" target="_blank">Recalculador ANEXO 14.1</a></li>
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
    
    <!--  Panel Reportes -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-copy"></i> Reportes CNV
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse4" class="pull-right">
                    <i class="fa fa-chevron-down"></i>
                </a>
            </h4>
        </div>
        <div id="collapse4" class="panel-collapse ">
            <div class="panel-body">         
                
                <li><a href="{module_url}reports/anexo_code/cnv_1" target="_blank">DDJJ para CNV</a></li>
                <li><a href="{module_url}reports/anexo_code/cnv_2" target="_blank">Evolución principales variables</a></li>
                <li><a href="{module_url}reports/anexo_code/cnv_3" target="_blank">Detalle de las inversiones del FDR</a></li>
                <li><a href="{module_url}reports/anexo_code/cnv_4" target="_blank">Evolución de las variables principales - Saldos promedios mensuales</a></li>
            </div>  
        </div>
    </div>

    <div class="panel-body">
                 <li><a href="{module_url}consultas/cuit" target="_blank">CONSULTA TIPO DE SOCIO POR CUIT - PARA SGR´S</a></li>
            </div>
</div>
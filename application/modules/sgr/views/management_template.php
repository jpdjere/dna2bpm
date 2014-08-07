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

    <div id="header">
        <div id="header-dna"></div>
        <div id="header-logos"></div>
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
                <li><a href="{module_url}reports/anexo_code/12" target="_blank">Reporte Anexo 12</a></li>
                <li><a href="{module_url}reports/anexo_code/14" target="_blank">Reporte Anexo 14</a></li>
                <li><a href="{module_url}reports/anexo_code/15" target="_blank">Reporte Anexo 15</a></li>
                <li><a href="{module_url}reports/anexo_code/201" target="_blank">Reporte Anexo 20.1</a></li>
            </div>
        </div>
    </div>


</div>
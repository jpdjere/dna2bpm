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



    <h1>SGR CNV</h1>   
    

    <!--  Panel Reportes -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-copy"></i> Reportes
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse3" class="pull-right">
                    <i class="fa fa-chevron-down"></i>
                </a>
            </h4>
        </div>
        <div id="collapse3" class="panel-collapse ">
            <div class="panel-body">         
                
                <li><a href="{module_url}reports/anexo_code/cnv_1" target="_blank">DDJJ para CNV</a></li>
                <li><a href="{module_url}reports/anexo_code/cnv_2" target="_blank">Evolución principales Variables</a></li>
                <li><a href="{module_url}reports/anexo_code/cnv_3" target="_blank">Detalle de las Inversiones del FDR</a></li>
            </div>
        </div>
    </div>


</div>
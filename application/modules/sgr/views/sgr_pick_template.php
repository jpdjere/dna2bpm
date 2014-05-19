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


   
    <h1>SGR PICK</h1>


    <form method="post" class="well" id="form" target="_blank" action="management/Set_sgr/">

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
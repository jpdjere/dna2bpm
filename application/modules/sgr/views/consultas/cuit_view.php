<!-- ==== Contenido ==== -->
<div class="container">     

    {if nombre_participe}

    <div style="text-align: center;">
        <img src="{logo}">
    </div>

    <!--/ NAVIGATION -->


    <div>
        <h3 style="text-align: center;">CONSULTA TIPO DE SOCIO POR CUIT - PARA SGR´S</h3>
    </div>

    {else}

    <div class="header_institucional">
      <img src="{base_url}dashboard/assets/img/logo_presidencia.png" class="presidencia_logo">
      <img src="{base_url}dashboard/assets/img/logo_secretaria.png" class="secretaria_logo">
    </div>


    <!--/ NAVIGATION -->

    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header ">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <h2><i class="fa fa-copy"></i> SGR CONSULTAS</h2>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <!-- Derecha -->
                <ul class="nav navbar-nav navbar-right">

                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>

    {/if}




    <!-- FORMULARIO -->
    <div class="row-fluid">
        {form_template}
    </div>


</div>
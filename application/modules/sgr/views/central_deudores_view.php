<!-- ==== Contenido ==== -->
<div class="container">     

    {if nombre_participe}

    <div style="text-align: center;">
        <img src="{logo}">
    </div>

    <!--/ NAVIGATION -->


    <div>

        <h3 style="text-align: center;">CENTRAL DE DEUDORES DEL SISTEMA DE SOCIEDADES DE GARANTIA RECIPROCA Y FONDOS DE GARANTIA PUBLICOS</h3>

        <h4>INFORME DEL SOCIO PARTCIPE</h4>

        <h4>{nombre_participe} – C.U.I.T.: {cuit_participe}	</h4>
    </div>

    {else}

    <div id="header">
        <div id="header-dna"></div>
        <div id="header-logos"></div>
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
                <h2><i class="fa fa-copy"></i> SGR CENTRAL</h2>
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
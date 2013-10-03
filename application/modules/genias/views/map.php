<!-- Submenu / Breadcrumbs -->
<div class="row-fluid " id="barra_user" >
    <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
         <li ></li> 
          <li class="pull-right perfil">
              <span id="status"></span>
              <a title="{usermail}">{username}</a> <i class="icon-angle-right"></i> <i class="{rol_icono}"></i> {rol}
          </li>
    </ul>

</div>
<!-- / Contenido -->
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span2">
            <!--Sidebar content-->
            <h4>
                Menu Mapa
            </h4>

            <ul class="nav nav-list general-sidenav">
                <!-- Mapa de Genias -->
                <li>
                    <label class="checkbox">
                        <input type="checkbox" value="genia">
                        Empresas Genias
                    </label>
                </li>
                <!-- Mapa de Empresas Dna� -->
                <li>
                    <label class="checkbox">
                        <input type="checkbox" value="dna2">
                        Empresas DNA&sup2;
                    </label>

                </li>
                <!-- Mapa de Empresas Dna� -->
                <li>

                    <a href="#" id="mapClear">
                        Limpiar Mapa
                    </a>
                </li>
            </ul>

        </div>
        <div class="span10">
            <!--Body content-->
            <div id="container" class="img-polaroid">    
                <div id="map_canvas" class="map"></div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; Genias 2013</p>
    </footer>
</div>

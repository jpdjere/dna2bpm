<!-- / Breadcrumbs -->
<div class="row-fluid " >
    <ul class="breadcrumb"  >
            <li><!-- Listado de Genias -->
                <div class="btn-group btn-small">
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    Mis Genias
                    <span class="caret"></span>
                    </a>
                <ul class="dropdown-menu" style="right:30px">
                {genias}
                  <li><a >{nombre}</a></li>
                  {/genias}
                </ul>
                </div>
            </li>
          <li><span class="divider">/</span></li>
          <li><a href="{module_url}">Dashboard</a> <span class="divider">/</span></li>
          <li><span class="divider">/</span></li>
          <li><a href="#">Map</a> <span class="divider">/</span></li>
          <li class="pull-right perfil">
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

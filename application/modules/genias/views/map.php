<!-- / Breadcrumbs -->
<div class="row-fluid " style="background-color:#f5f5f5;">  
<div class="span12" style="padding: 3px">
<ul class="breadcrumb navbar-static-top pull-left"  >
  <li><a href="{module_url}">Dashboard</a> <span class="divider">/</span></li>
  <li><a href="#">Mapa</a> <span class="divider">/</span></li>
</ul>
<button class="btn pull-right"  type="button" ><i class="icon-off"></i> Salir</button>
</div>
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

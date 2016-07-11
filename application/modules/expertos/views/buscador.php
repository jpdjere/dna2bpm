<div class="row"> 
 <div class="col-xs-8">
  <h4>Buscador</h4>
  <div class="spacer20"></div>
  
  <div class="input-group">
            <div class="input-group-btn search-panel">
                <button type="button" id="boton" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                	<span id="search_concept">Buscar por</span> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="#cuit">CUIT (sin guiones)</a></li>
                  <li><a href="#razonsocial">Razón Social</a></li>
                </ul>
            </div>
            <input type="hidden" id="parametro"  value="all" id="search_param">         
            <input type="text" class="form-control" id="query" placeholder="Buscar...">
            <span class="input-group-btn">
                <button class="btn btn-primary" type="button" id="buscador"><span class="glyphicon glyphicon-search"></span></button>
            </span>
        </div>
  </div>
  
 </div>
 
 <div id="tabla" hidden>
  {tabla_contenido}
 </div>

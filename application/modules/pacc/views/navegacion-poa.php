
	<div class="row">
    
		<div class="col-lg-6 col-md-6 col-sm-6">
          <div class="update-nag">
            <div class="update-split"><i class="glyphicon glyphicon-folder-open"></i></div>
            <div class="update-text"><a href="{module_url}poa_report">Cargar un POA por archivo .xls</a> </div>
          </div>
        </div>
    
        <div class="col-lg-6 col-md-6 col-sm-6">
          <div class="update-nag">
            <div class="update-split update-info"><i class="glyphicon glyphicon-refresh"></i></div>
            <div class="update-text"><a href="{module_url}poa/form">Cargar un POA online</a></div>
          </div>
        </div>

         <div class="col-lg-6 col-md-6 col-sm-6">
          <div class="update-nag">
            <div class="update-split update-danger"><i class="glyphicon glyphicon-search"></i></div>
            <div class="update-text"><a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Ver un POA</a></div>
          </div>
          <div id="collapseTwo" class="panel-collapse collapse">
            <form id="form" method="POST" action="{module_url}poa/ver_poa/">
              <div class="form-group">
                <select name="id" class="form-control" >
                  {reportes}
                  <option value="{_id}">{filename}</option>
                  {/reportes}
                </select>
              </div>
              <button type="submit" class="btn btn-primary">Buscar</button>
              <div class="spacer10"></div>
            </form>
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6">
          <div class="update-nag">
            <div class="update-split update-success"><i class="glyphicon glyphicon-edit"></i></div>
            <div class="update-text"><a href="{module_url}poa/carga_componentes">Configurar áreas y componentes</a></div>
          </div>
        </div>
      
	</div>
</div>

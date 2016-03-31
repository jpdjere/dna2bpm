<form class="form tour-incubar incubar-cinco">
  <div class="row">
    <div class="col-lg-12">
        <label for="incubadoras" class="control-label">Incubadoras</label>
    </div>
  </div>
    <div class="form-group">
      <div class="row ">
          <div class="col-lg-4 ">
            <select class="form-control" id="Inc">
                 <option>...</option>
                  {incubadoras}
                  <option value="{id}">{nombre}</option>
                  {/incubadoras}
                </select>
            <div class="spacer10"></div>
          </div>
        <div class="col-xs-6 col-lg-2">
          <div class="form-group">
      	    <button class="btn btn-primary" id="buscador-estado" type="button">Buscar</button>
          </div>
        </div>
      </div>
    </div>
</form> 
<table class="table table-bordered table-hover" id="tabla-estado">
    <thead>
          <tr role="row">
              <th>Provincia</th>
              <th>Proyectos Presentados</th>
              <th>Proyectos Pre-Aprobados</th>
              <th>Proyectos Aprobados</th>
              <th>Proyectos Rechazados</th>
              <th>Proyectos Finalizados</th>
              <th>Proyectos Desembolsados</th>
              <th>Desembolsos Realizados</th>
          </tr>
    </thead>
          <tbody>
            {tabla_estado}  
          </tbody>
      </table>
      <div class="row">
        <div class="col-lg-12">
            <div style="margin-top: 10px" class="pull-right"><button class="btn btn-block btn-default">Exportar a Excel</button></div>
      </div>
      </div>
                  
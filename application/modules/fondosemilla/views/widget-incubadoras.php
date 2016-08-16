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
                  <option value="{owner}">{nombre}</option>
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
              <th>N° Proyecto</th>
              <th>Nombre del Proyecto</th>
              <th>Nombre</th>              
              <th>Apellido</th>              
              <th>Provincia</th>
              <th>Localidad</th>
              <th>DNI</th>
              <th>E-mail</th>
          </tr>
    </thead>
          <tbody>
            {tabla_estado}  
          </tbody>
      </table>

                  
<form class="form tour-incubar incubar-cinco">
  <div class="row">
    <div class="col-lg-12">
        <label for="incubadoras" class="control-label">DNI</label>
    </div>
  </div>
    <div class="form-group">
      <div class="row ">
          <div class="col-lg-4 ">
              <input type="text" name="CUIT" id="CUIT" class="form-control" placeholder="Ingrese CUIT o DNI a consultar"required>
            <div class="spacer10"></div>
          </div>
        <div class="col-xs-6 col-lg-2">
          <div class="form-group">
      	    <button class="btn btn-primary" id="buscador-proyectos" type="button">Buscar</button>
          </div>
        </div>
      </div>
    </div>
</form> 
<table class="table table-bordered table-hover" id="tabla-buscador">
    <thead>
          <tr role="row">
              <th>Nro de Proyecto</th>
              <th>Nombre</th>
              <th>Apellido</th>
              <th>E-mail</th>
              <th>Ver Detalle</th>
          </tr>
    </thead>
          <tbody>
            {tabla_estado}  
          </tbody>
      </table>

                  
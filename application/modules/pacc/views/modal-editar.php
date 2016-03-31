  <div id="modal-editar" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="mySmallModalLabel2">Nuevo Componente</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                        <label for="Input3">Seleccione que tipo de elemento desea editar</label>
                        <select class="form-control" id="elemento">
                         <option value="1">Componente</option>
                         <option value="2">Subcomponente</option>
                         </select>
                      </div>
                      <div class="form-group" id="area-hidden">
                        <label for="Input3">Seleccione el área en que lo desea agregar</label>
                        <select class="form-control" id="area">
                         <option value="1">Área Planificación</option>
                          <option value="2">Apoyo directo a Empresas</option>
                         </select>
                      </div>
                      <div id="componente" class="form-group" hidden>
                        <label for="Input1">Ingrese el Componente padre</label>
                        <input value="-" id="comp" type="text" class="form-control" hidden="hidden" required>
                      </div>
                   <div class="form-group">
                        <label for="Input1">Código</label>
                        <input id="codigo" type="text" class="form-control" required>
                      </div>
                      <div class="form-group">
                        <label for="Input2">Descripción</label>
                        <input id="descripcion" type="text" class="form-control"required>
                      </div>
                      
                      <div class="row">
                        <div class="col-xs-12">
                      <div class="checkbox pull-left">
                        <label class="pad-left-modal">
                        
                        </label>
                      </div>
                      <div class="pull-right">
                      <button id="boton-editar" class="btn btn-success">Aceptar</button>
                      <button type="reset" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                  </div>
                    </div>
                </div>
                  
                </div>
            </div>
        </div>
    </div>
<div id="modal-contrato" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="mySmallModalLabel">Detalle del contrato</h4>
                </div>
                <div class="modal-body">
                          <div class="form-group">
                            <label for="Input5">Fecha de alta contrato</label>
                             <input name="..." type="text" class="form-control" value="{FECHA_ALTA_CONTRATO}">
                          </div>
                          <div class="form-group">
                            <label for="Input2">Categoría de proceso</label>
                             <select class="form-control">
                              <option>{CATEGORIA_PROCESO}</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="Input6">Rubro</label>
                             <select class="form-control">
                              <option>{RUBRO}</option>
                            </select>
                          </div>
                           <div class="form-group">
                            <label for="Input6">Método de adquisición</label>
                             <select class="form-control">
                              <option>{METODO_ADQUISICION}</option>
                            </select>
                          </div>
                          <div class="form-group">
                              <label class="control-label" for="message">Descripción del contrato</label>
                                <textarea class="form-control" id="message" name="descripción" rows="5" value="{DESCRIPCION}"></textarea>
                          </div>
                           <div class="form-group">
                            <label for="Input6">Moneda</label>
                             <select class="form-control">
                              <option>{MONEDA}</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="Input5">Cotización</label>
                             <input name="..." type="text" class="form-control" value="{COTIZACION}">
                          </div>
                          <div class="form-group">
                            <label for="Input5">Costo estimado en pesos</label>
                             <input name="..." type="text" class="form-control" value="{COSTO_EST_PESOS}" >
                          </div>
                          <div class="form-group">
                            <label for="Input5">Revisión</label>
                             <input name="..." type="text" class="form-control" value="{REVISION}" >
                          </div>
                          <div class="form-group">
                            <label for="Input5">Responsable</label>
                             <input name="..." type="text" class="form-control" value="{RESPONSABLE}">
                          </div>
                          <div class="form-group">
                            <label for="Input5">% Pari Passu</label>
                             <input name="..." type="text" class="form-control" value="{PARI_PASSU}">
                          </div>
                          <h5 style="border-bottom: 1px solid #e5e5e5; padding-bottom: 5px">Financiamiento</h5>
                          <div class="row">
                          <div class="col-md-6">
                           <div class="form-group">
                               
                            <label for="Input6">Moneda</label>
                             <select class="form-control">
                              <option>Pesos</option>
                              <option>2</option>
                              <option>3</option>
                              <option>4</option>
                              <option>5</option>
                            </select>
                            </div>
                          </div>
                           <div class="col-md-6">
                           <div class="form-group">
                               
                            <label for="Input6">Moneda</label>
                             <select class="form-control">
                              <option>Pesos</option>
                              <option>2</option>
                              <option>3</option>
                              <option>4</option>
                              <option>5</option>
                            </select>
                            </div>
                          </div>
                          </div>
                          <h5 style="border-bottom: 1px solid #e5e5e5; padding-bottom: 5px">Cronograma de contrato estimado</h5>
                          <div class="row">
                          <div class="col-md-6">
                           <div class="form-group">
                               
                            <label for="Input5">Publicación AEA</label>
                             <input name="..." type="date" class="form-control" value="{REAL_PUBLIC_AEA}">
                            </div>
                          </div>
                           <div class="col-md-6">
                           <div class="form-group">
                               <label for="Input5">Terminación contrato</label>
                             <input name="..." type="date" class="form-control" value="{REAL_FIN_CONT}">
                            </div>
                          </div>
                          </div>
                           <h5 style="border-bottom: 1px solid #e5e5e5; padding-bottom: 5px">Cronograma de pagos estimado</h5>
                           <div class="form-group">
                               
                            <label for="Input5">Cantidad de días firma contrato</label>
                             <input name="..." type="date" class="form-control" id="Input5">
                            </div>
                            <div class="form-group">
                               
                            <label for="Input6">Porcentaje de pago</label>
                             <select class="form-control">
                              <option>10%</option>
                              <option>2</option>
                              <option>3</option>
                              <option>4</option>
                              <option>5</option>
                            </select>
                            </div>
                          <div class="row">
                            <div class="col-xs-12">
                              <div class="pull-right">
                              <button type="reset" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                              </div>
                            </div>
                           </div>
                </div>
            </div>
        </div>
    </div>
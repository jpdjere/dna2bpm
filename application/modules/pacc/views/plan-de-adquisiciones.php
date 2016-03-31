
            <div class="panel panel-default">
                <div class="panel-heading c-list">
                    <span class="title">Pantalla principal</span>
                    <ul class="pull-right c-controls">
                    	<li><a href="#modal-cronograma" data-command="toggle-search" data-toggle="tooltip" data-placement="top" title="Editar fechas reales al contrato"><i class="fa fa-calendar"></i></a></li>
                    	<li><a href="#modal-pago" data-command="toggle-search" data-toggle="tooltip" data-placement="top" title="Agregar plan de pagos"><i class="fa fa-money"></i></a></li>
                    	<li><a data-toggle="tooltip" data-placement="top" title="Ver detalle de plan de pagos"><i class="fa fa-book"></i></a></li>
                        <li><a data-command="toggle-search" data-toggle="tooltip" data-placement="top" title="Ver detalle de contrato"><i class="fa fa-info-circle"></i></a></li>
                        <li><a href="#modal-nuevo-editar" data-toggle="tooltip" data-placement="top" title="Nuevo contrato"><i class="glyphicon glyphicon-plus"></i></a></li>
                        <li><a href="#modal-solo-editar" data-command="toggle-search" data-toggle="tooltip" data-placement="top" title="Editar contrato"><i class="fa fa-edit"></i></a></li>
                        <li><a href="#" data-command="toggle-search" data-toggle="tooltip" data-placement="top" title="Eliminar contrato"><i class="fa fa-trash-o"></i></a></li>
                        
                    </ul>
                </div>
                 <div class="panel-body">
                  <div class="table-responsive">
                 	<table class="table table-bordered">
			   <thead>
			      <tr>
			      	<th class="info-table-center pad-thead" rowspan="3" scope="col"></th>
			         <th class="info-table-center pad-thead" rowspan="2" scope="col">Descripción del contrato</th>
			         <th class="info-table-center pad-thead" scope="col" rowspan="2">Costo estimado u$s</th>
			        <th class="info-table-center" scope="col" colspan="2">Fuente de Financiamiento y Porcentaje</th>
			        <th class="info-table-center" scope="col" colspan="2">Fechas estimadas</th>
			        <th class="info-table-center" scope="col" colspan="2">Fechas reales</th>
			        <th class="info-table-center" scope="col" colspan="2">Plan de pagos total</th>
			  </tr>
			  <tr>
			         <th class="info-table-center">BID %</th>
			         <th class="info-table-center">Local/Otro %</th>
			         <th class="info-table-center">Publicación AEA</th>
			         <th class="info-table-center">Terminación contrato</th>
			          <th class="info-table-center">Publicación AEA</th>
			         <th class="info-table-center">Terminación contrato</th>
			          <th class="info-table-center">Porcentaje Total</th>
			         <th class="info-table-center">Monto Total</th>
			         
			      </tr>
			      <tr>
			      	<th colspan="2" scope="col">1. Gastos operativos</th>
			      	<th colspan="4" scope="col"></th>
			      	<th colspan="2" scope="col"></th>
			      	<th colspan="2" scope="col"></th>
			      </tr>
			     
			   </thead>
			   <tbody id="MyTable" class="size-table-font">
			     {contratos}
			      <tr >
			      	<td class="text-center">
			      		<input type="radio" name="radio" class="Radios" value="{_id}" required>
		      		</td>
			         <td>{DESCRIPCION}</td>
			         <td>{COSTO_EST_PESOS}</td>
			         <td>{FIN_BID}</td>
			         <td>{FIN_LOC}</td>
			         <td>{PUBLIC_AEA}</td>
			         <td>{FIN_CONT}</td>
			         <td>{REAL_PUBLIC_AEA}</td>
			         <td>{REAL_FIN_CONT}</td>
			         <td>{PORCENTAJE_PAGO}</td>
			         <td>2444</td>
			      </tr>
			      {/contratos}
			      <tr>
			      	<td>TOTAL GENERAL</td>
			      	<td>{TOTAL_GENERAL}</td>
			      </tr>
			      <tr>
			      	<td>Aporte Local</td>
			      	<td>{APORTE_LOCAL}</td>
			      </tr>
			      <tr>
			      	<td>BID</td>
			      	<td>{BID_TOTAL}</td>
			      </tr>

			   </tbody>
			</table>
                </div>   
                   
                </div>
            </div>
          </div>  
        
         <!-- Modales 1.cronograma -->
    <div id="modal-cronograma" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="mySmallModalLabel">Cronograma de contrato real</h4>
                </div>
                <div class="modal-body">
                      <div class="form-group">
                        <label for="Input1">Publicación AEA</label>
                         <input name="AEA" type="date" class="form-control" id="Date_2">
                      </div>
                      <div class="form-group">
                        <label for="Input2">Terminación contrato</label>
                         <input name="FIN_CONT" type="date" class="form-control" id="Date_1">
                      </div>
                      <div class="row">
                        <div class="col-xs-12">
                      <div class="pull-right">
                      <button id="edit_fecha_plan_de_pagos" class="btn btn-success">Aceptar</button>
                      <button type="reset" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                  </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modales 2.pago -->
    <div id="modal-pago" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="mySmallModalLabel">Nuevo pago / Editar pago</h4>
                </div>
                <div class="modal-body">
                    <h5 style="border-bottom: 1px solid #e5e5e5; padding-bottom: 5px">Cronograma de pagos estimado</h5>
                    <div class="row">
                        <div class="col-lg-6">
                            <p>Cantidad de días firma contrato</p>
                        </div>
                        <div class="col-lg-6">
                            <p>30</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <p>Porcentaje de pago</p>
                        </div>
                        <div class="col-lg-6">
                            <p>10%</p>
                        </div>
                    </div>
                    <h5 style="border-bottom: 1px solid #e5e5e5; padding-bottom: 5px">Contrato real</h5>
                          <div class="form-group">
                            <label for="Input3">Porcentaje de pago real</label>
                             <input name="PORCENTAJE" type="text" class="form-control" id="PORCENTAJE">
                          </div>
                          <div class="form-group">
                            <label for="Input2">Días reales</label>
                             <input name="DIAS" type="text" class="form-control" id="DIAS">
                          </div>
                    <h5 style="border-bottom: 1px solid #e5e5e5; padding-bottom: 5px">Ingresar pago</h5>
                          <div class="form-group">
                            <label for="Input4">Fecha de pago</label>
                             <input name="FECHA_DE_PAGO" type="text" class="form-control" id="FECHA_DE_PAGO">
                          </div>
                          <div class="form-group">
                            <label for="Input2">Pago realizado</label>
                             <input name="MONTO" type="text" class="form-control" id="MONTO">
                          </div>
                          <div class="row">
                            <div class="col-xs-12">
                              <div class="pull-right">
                              <button id="agregar_plan_de_pagos" class="btn btn-success">Aceptar</button>
                              <button type="reset" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                              </div>
                            </div>
                           </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modales 3.detalle -->
     <div id="modal-contrato" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="mySmallModalLabel">Detalle del contrato</h4>
                </div>
                <div class="modal-body">
                    <form id="form" method="POST" action="{module_url}poa/save_componentes/">
                          <div class="form-group">
                            <label for="Input5">Fecha de alta contrato</label>
                             <input name="..." type="text" class="form-control" id="Input5">
                          </div>
                          <div class="form-group">
                            <label for="Input2">Categoría de proceso</label>
                             <select class="form-control">
                              <option>Bienes</option>
                              <option>2</option>
                              <option>3</option>
                              <option>4</option>
                              <option>5</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="Input6">Rubro</label>
                             <select class="form-control">
                              <option>Gastos operativos</option>
                              <option>2</option>
                              <option>3</option>
                              <option>4</option>
                              <option>5</option>
                            </select>
                          </div>
                           <div class="form-group">
                            <label for="Input6">Método de adquisición</label>
                             <select class="form-control">
                              <option>Concurso</option>
                              <option>2</option>
                              <option>3</option>
                              <option>4</option>
                              <option>5</option>
                            </select>
                          </div>
                          <div class="form-group">
                              <label class="control-label" for="message">Descripción del contrato</label>
                                <textarea class="form-control" id="message" name="descripción" rows="5"></textarea>
                          </div>
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
                          <div class="form-group">
                            <label for="Input5">Cotización</label>
                             <input name="..." type="text" class="form-control" id="Input5">
                          </div>
                          <div class="form-group">
                            <label for="Input5">Costo estimado en pesos</label>
                             <input name="..." type="text" class="form-control" id="Input5">
                          </div>
                          <div class="form-group">
                            <label for="Input5">Revisión</label>
                             <input name="..." type="text" class="form-control" id="Input5">
                          </div>
                          <div class="form-group">
                            <label for="Input5">Responsable</label>
                             <input name="..." type="text" class="form-control" id="Input5">
                          </div>
                          <div class="form-group">
                            <label for="Input5">% Pari Passu</label>
                             <input name="..." type="text" class="form-control" id="Input5">
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
                             <input name="..." type="date" class="form-control" id="Input5">
                            </div>
                          </div>
                           <div class="col-md-6">
                           <div class="form-group">
                               <label for="Input5">Terminación contrato</label>
                             <input name="..." type="date" class="form-control" id="Input5">
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
                              <button type="submit" class="btn btn-success">Aceptar</button>
                              <button type="reset" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                              </div>
                            </div>
                           </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modales 4.plan pagos -->
    <div id="modal-plan" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="mySmallModalLabel">Detalle de Plan de Pagos</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
	        
            <div class="panel panel-default">
                <div class="panel-heading c-list">
                    <ul class="pull-right c-controls">
                    	<li><a href="#modal-pagos" data-command="toggle-search" data-toggle="tooltip" data-placement="top" title="Editar pago"><i class="fa fa-calendar"></i></a></li>
                    	<li><a href="#" data-command="toggle-search" data-toggle="tooltip" data-placement="top" title="Eliminar pago"><i class="fa fa-money"></i></a></li>
                    </ul>
                </div>
                 <div class="panel-body">
                 	<table class="table table-bordered">
			   <thead>
			  <tr>
			         <th class="info-table-center"><input type="radio" name="optionsRadios" id="optionsRadios1" value="option1"></th>
			         <th class="info-table-center">Porcentaje</th>
			         <th class="info-table-center">Monto</th>
			         <th class="info-table-center">Días</th>
			          <th class="info-table-center">Fecha de pago</th>
			         
			      </tr>
			   </thead>
			   <tbody id="MyTable" class="size-table-font">
			      <tr >
			      	<td>
			      		<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1">
		      		</td>
			         <td>%10  </td>
			         <td>$27282</td>
			         <td>30</td>
			         <td>12/10/2014</td>
			         
			      </tr>

			   </tbody>
			</table>
                   
                   
                </div>
            </div>
          </div>  
                </div>
            </div>
        </div>
    </div>
    <!-- Modales 5.detalle -->
     <div id="modal-nuevo-editar" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="mySmallModalLabel">Nuevo contrato / Editar contrato</h4>
                </div>
                <div class="modal-body">
                    <form id="form" method="POST" action="{module_url}plan_de_adquisiciones/agregar_contrato/">
                          <div class="form-group">
                            <label for="Input5">Fecha de alta contrato</label>
                             <input name="FECHA_ALTA_CONTRATO" type="text" class="form-control" id="Input5">
                          </div>
                          <div class="form-group">
                            <label for="Input2">Categoría de proceso</label>
                             <select name="CATEGORIA_PROCESO"class="form-control">
                              <option>Bienes</option>
                              <option>2</option>
                              <option>3</option>
                              <option>4</option>
                              <option>5</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="Input6">Rubro</label>
                             <select name="RUBRO" class="form-control">
                              <option>Gastos operativos</option>
                              <option>2</option>
                              <option>3</option>
                              <option>4</option>
                              <option>5</option>
                            </select>
                          </div>
                           <div class="form-group">
                            <label for="Input6">Método de adquisición</label>
                             <select name="METODO_ADQUISICION" class="form-control">
                              <option>Concurso</option>
                              <option>2</option>
                              <option>3</option>
                              <option>4</option>
                              <option>5</option>
                            </select>
                          </div>
                          <div class="form-group">
                              <label class="control-label" for="message">Descripción del contrato</label>
                                <textarea class="form-control" id="message" name="DESCRIPCION" rows="5"></textarea>
                          </div>
                           <div class="form-group">
                            <label for="Input6">Moneda</label>
                             <select name="MONEDA" class="form-control">
                              <option>Pesos</option>
                              <option>2</option>
                              <option>3</option>
                              <option>4</option>
                              <option>5</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="Input5">Cotización</label>
                             <input name="COTIZACION" type="text" class="form-control" id="Input5">
                          </div>
                          <div class="form-group">
                            <label for="Input5">Costo estimado en pesos</label>
                             <input name="COSTO_EST_PESOS" type="text" class="form-control" id="Input5">
                          </div>
                          <div class="form-group">
                            <label for="Input5">Revisión</label>
                             <input name="REVISION" type="text" class="form-control" id="Input5">
                          </div>
                          <div class="form-group">
                            <label for="Input5">Responsable</label>
                             <input name="RESPONSABLE" type="text" class="form-control" id="Input5">
                          </div>
                          <div class="form-group">
                            <label for="Input5">% Pari Passu</label>
                             <input name="PARI_PASSU" type="text" class="form-control" id="Input5">
                          </div>
                          <h5 style="border-bottom: 1px solid #e5e5e5; padding-bottom: 5px">Financiamiento</h5>
                          <div class="row">
                          <div class="col-md-6">
                           <div class="form-group">
                               
                            <label for="Input6">BID</label>
                             <select name="FIN_BID" class="form-control">
                              <option>%40</option>
                              <option>2</option>
                              <option>3</option>
                              <option>4</option>
                              <option>5</option>
                            </select>
                            </div>
                          </div>
                           <div class="col-md-6">
                           <div class="form-group">
                               
                            <label for="Input6">Local/Otro</label>
                             <select name="FIN_LOC" class="form-control">
                              <option>%60</option>
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
                             <input name="PUBLIC_AEA" type="date" class="form-control" id="Input5">
                            </div>
                          </div>
                           <div class="col-md-6">
                           <div class="form-group">
                               <label for="Input5">Terminación contrato</label>
                             <input name="FIN_CONT" type="date" class="form-control" id="Input5">
                            </div>
                          </div>
                          </div>
                           <h5 style="border-bottom: 1px solid #e5e5e5; padding-bottom: 5px">Cronograma de pagos estimado</h5>
                           <div class="form-group">
                               
                            <label for="Input5">Cantidad de días a firma contrato estimado</label>
                             <input name="CANT_D_FIRMA" type="date" class="form-control" id="Input5">
                            </div>
                            <div class="form-group">
                               
                            <label for="Input6">Porcentaje de pago estimado</label>
                             <select name="PORCENTAJE_PAGO" class="form-control">
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
                              <button type="submit" class="btn btn-success">Aceptar</button>
                              <button type="reset" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                              </div>
                            </div>
                           </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div id="modal-solo-editar" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="mySmallModalLabel">Nuevo contrato / Editar contrato</h4>
                </div>
                <div class="modal-body">
                    <form id="form_editar" >
                          <div class="form-group">
                            <label for="Input5">Fecha de alta contrato</label>
                             <input name="FECHA_ALTA_CONTRATO" type="text" class="form-control" id="Input5">
                          </div>
                          <div class="form-group">
                            <label for="Input2">Categoría de proceso</label>
                             <select name="CATEGORIA_PROCESO"class="form-control">
                              <option>Bienes</option>
                              <option>2</option>
                              <option>3</option>
                              <option>4</option>
                              <option>5</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="Input6">Rubro</label>
                             <select name="RUBRO" class="form-control">
                              <option>Gastos operativos</option>
                              <option>2</option>
                              <option>3</option>
                              <option>4</option>
                              <option>5</option>
                            </select>
                          </div>
                           <div class="form-group">
                            <label for="Input6">Método de adquisición</label>
                             <select name="METODO_ADQUISICION" class="form-control">
                              <option>Concurso</option>
                              <option>2</option>
                              <option>3</option>
                              <option>4</option>
                              <option>5</option>
                            </select>
                          </div>
                          <div class="form-group">
                              <label class="control-label" for="message">Descripción del contrato</label>
                                <textarea class="form-control" id="message" name="DESCRIPCION" rows="5"></textarea>
                          </div>
                           <div class="form-group">
                            <label for="Input6">Moneda</label>
                             <select name="MONEDA" class="form-control">
                              <option>Pesos</option>
                              <option>2</option>
                              <option>3</option>
                              <option>4</option>
                              <option>5</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="Input5">Cotización</label>
                             <input name="COTIZACION" type="text" class="form-control" id="Input5">
                          </div>
                          <div class="form-group">
                            <label for="Input5">Costo estimado en pesos</label>
                             <input name="COSTO_EST_PESOS" type="text" class="form-control" id="Input5">
                          </div>
                          <div class="form-group">
                            <label for="Input5">Revisión</label>
                             <input name="REVISION" type="text" class="form-control" id="Input5">
                          </div>
                          <div class="form-group">
                            <label for="Input5">Responsable</label>
                             <input name="RESPONSABLE" type="text" class="form-control" id="Input5">
                          </div>
                          <div class="form-group">
                            <label for="Input5">% Pari Passu</label>
                             <input name="PARI_PASSU" type="text" class="form-control" id="Input5">
                          </div>
                          <h5 style="border-bottom: 1px solid #e5e5e5; padding-bottom: 5px">Financiamiento</h5>
                          <div class="row">
                          <div class="col-md-6">
                           <div class="form-group">
                               
                            <label for="Input6">BID</label>
                             <select name="FIN_BID" class="form-control">
                              <option>%40</option>
                              <option>2</option>
                              <option>3</option>
                              <option>4</option>
                              <option>5</option>
                            </select>
                            </div>
                          </div>
                           <div class="col-md-6">
                           <div class="form-group">
                               
                            <label for="Input6">Local/Otro</label>
                             <select name="FIN_LOC" class="form-control">
                              <option>%60</option>
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
                             <input name="PUBLIC_AEA" type="date" class="form-control" id="Input5">
                            </div>
                          </div>
                           <div class="col-md-6">
                           <div class="form-group">
                               <label for="Input5">Terminación contrato</label>
                             <input name="FIN_CONT" type="date" class="form-control" id="Input5">
                            </div>
                          </div>
                          </div>
                           <h5 style="border-bottom: 1px solid #e5e5e5; padding-bottom: 5px">Cronograma de pagos estimado</h5>
                           <div class="form-group">
                               
                            <label for="Input5">Cantidad de días a firma contrato estimado</label>
                             <input name="CANT_D_FIRMA" type="date" class="form-control" id="Input5">
                            </div>
                            <div class="form-group">
                               
                            <label for="Input6">Porcentaje de pago estimado</label>
                             <select name="PORCENTAJE_PAGO" class="form-control">
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
                              <button id="editar_contrato" class="btn btn-success">Aceptar</button>
                              <button type="reset" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                              </div>
                            </div>
                           </div>
                    </form>
                </div>
            </div>
        </div>
    </div>  
        
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
                <div style="min-height: 53px" class="panel-heading c-list">
                    <ul class="pull-right c-controls">
                    	<li><a href="#modal-pagos" data-command="toggle-search" data-toggle="tooltip" data-placement="top" title="Editar pago"><i class="fa fa-calendar"></i></a></li>
                    	<li><a href="#" data-command="toggle-search" data-toggle="tooltip" data-placement="top" title="Eliminar pago"><i class="fa fa-money"></i></a></li>
                    </ul>
                </div>
                 <div class="panel-body">
                 	<div class="table-responsive">
                 	<table class="table table-bordered">
			   <thead>
			  <tr>
			         <th class="info-table-center text-center"><input type="radio" name="optionsRadios" id="optionsRadios1" value="option1"></th>
			         <th class="info-table-center">Porcentaje</th>
			         <th class="info-table-center">Monto</th>
			         <th class="info-table-center">Días</th>
			          <th class="info-table-center">Fecha de pago</th>
			         
			      </tr>
			   </thead>
			   <tbody id="MyTable" class="size-table-font">
			       {pagos}
			      <tr >
			      	<td class="text-center">
			      		<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1">
		      		</td>
			         <td>{PORCENTAJE}</td>
			         <td>{MONTO}</td>
			         <td>{DIAS}</td>
			         <td>{FECHA_DE_PAGO}</td>
			      </tr>
			   </tbody>
			   {/pagos}
			</table>
                   </div>
                   
                </div>
            </div>
          </div>  
                </div>
            </div>
        </div>
    </div>
    <div id="ModalWrapper"></div>
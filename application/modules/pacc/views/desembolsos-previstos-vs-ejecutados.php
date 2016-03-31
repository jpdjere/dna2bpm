<div class="row">
    <div class="col-md-12">
        <h4 style="margin-top: 0px;">Seleccione un período para consultar los desembolsos:</h4>
    </div>
<div class="date-form">  
    <div class="form-horizontal">
        <div class="col-md-7 col-sm-7">
            <div class="control-group">
                <label for="date-picker-2" class="control-label">Desde</label>
                <div class="controls">
                    <div class="input-group col-xs-12">
                        <input id="date-picker1" type="text" class="date-picker form-control" />

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7 col-sm-7">
            <div class="control-group">
                <div class="spacer10"></div>
                <label for="date-picker-3" class="control-label">Hasta</label>
                <div class="controls">
                    <div class="input-group col-xs-12">
                        <input id="date-picker2" type="text" class="date-picker form-control" />
                  
                        
                    </div>
                </div>
            </div>
        </div>
    </div> 

</div>

</div>
<div class="row">
    <div class="col-md-2"> 
        <div class="spacer15"></div>
        <button id="boton-consultar" type="button" class="btn btn-primary col-md-12">Buscar</button>
    </div>
</div>



<div class="table-responsive" id="hide" hidden>
  <div class="spacer30"></div>  
<table class="table table-bordered" id="tabla-administracion">
  <tbody><tr>
    <th>N° de Proyecto</th>
     <th>Componente</th>
    <th>Desembolsos Previstos</th>
    <th>Desembolsos Solicitados</th>
     <th>Diferencia</th>
  </tr>
  {data}
  <tr>
    <td>{ip}</td>
    <td></td>
    <td>{previsto}</td>
    <td>{desembolso}</td>
    <td>{diferencia}</td>
  </tr>
  {/data}
</tbody></table>
</div>

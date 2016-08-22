<H3>Montos cargados:</H3>
<div class="col-lg-9">
<table class="table table-striped">
    <thead>
        <tr><td>Resolución</td><td>Destinos</td><td>Monto Máximo</td><td>Acciones</td></tr>
    </thead>
    <tboby>
    {content}
        <tr>
            <td>{resolucion name}</td>
            <td>{destino}{name}, {/destino}</td>
            <td>{monto}</td>
            <td>
                <button type="button" class="btn btn-default edit" mongoid="{_id}" resolucion="{resolucion value}" destino[]="{destino}{value},{/destino}" monto="{monto}">
                    <span class="glyphicon glyphicon-pencil"></span>
                </button>
                <button type="button" class="btn btn-default remove" mongoid="{_id}">
                    <span class="glyphicon glyphicon-remove"></span>
                </button>
            </td>
        </tr>
    {/content}
    </tboby>
    <tfooter>
        <form id="insertar" method="post">
        <tr>
            <td>
                <select name="resolucion" class="form-control" required>
                    <option selected disabled value=""></option>
                    {resoluciones}
                    <option value="{id}">{resolucion}</option>
                    {/resoluciones}
                </select>
            </td>
            <td>
              <div style="overflow:scroll; height:80px; overflow-x: hidden;">
                {destinos}
                <div class="checkbox required">
                    <label class="checkbox-custom" data-initialize="checkbox">
                        <input type="checkbox" class="sr-only insertar-checkbox" name="destino[]" value="{id}" form="insertar">
                        <span class="checkbox-label">{destino}</span>
                    </label>
                </div>
                {/destinos}
              </div>
            </td>
            <td><input type="number" name="monto" class="form-control" min="0" required/></td>
            <td><input type="submit" value="Insertar" class="btn btn-default"/></td>
        </tr>
        </form>
    </tfooter>
</table>
</div>
<div class="col-lg-3" id="col2">
    <h2>Editar Monto Asignado:</h2>
    <form id="editar" method="post">
        <div class="form-group">
            <label for="resolucion">Resolución:</label>
            <select name="resolucion" id="resolucion" class="form-control" required>
                <option selected disabled value=""></option>
                {resoluciones}
                <option value="{id}">{resolucion}</option>
                {/resoluciones}
            </select>
        </div>
        <div class="form-group">
            <label for="destino[]">Destinos</label>
            <div style="overflow:scroll; height:80px; overflow-x: hidden;">
                {destinos}
                <div class="checkbox">
                    <label class="checkbox-custom" data-initialize="checkbox">
                        <input type="checkbox" class="sr-only checkbox-edit" name="destino[]" value="{id}" id="destino-checkbox-{id}" form="editar">
                        <span class="checkbox-label">{destino}</span>
                    </label>
                </div>
                {/destinos}
              </div>
        </div>
        <div class="form-group">
            <label for="monto">Monto Máximo</label>
            <input type="number" id="monto" name="monto" class="form-control" min="0" required/>
        </div>
            <input type="hidden" name="_id" id="_id"/>
        <div class="form-group">
            <input type="submit" value="Guardar" class="btn btn-default"/>
        </div>
    </form>
</div>

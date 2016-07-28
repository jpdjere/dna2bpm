<H3>Resoluciones cargadas:</H3>
<div class="col-lg-7">
<table class="table table-striped">
    <thead>
        <tr><td>Resolución</td><td>Acciones</td></tr>
    </thead>
    <tboby>
    {content}
        <tr>
            <td>{resolucion}</td>
            <td>
                <button type="button" class="btn btn-default edit" id="{id}" resolucion="{resolucion}">
                    <span class="glyphicon glyphicon-pencil"></span>
                </button>
                <button type="button" class="btn btn-default remove" id="{id}">
                    <span class="glyphicon glyphicon-remove"></span>
                </button>
            </td>
        </tr>
    {/content}
    </tboby>
    <tfooter>
        <form id="insertar" method="post">
        <tr>
            <td><input type="text" name="resolucion" class="form-control" maxlength="20" required/></td>
            <td><input type="submit" value="Insertar" class="btn btn-default"/></td>
        </tr>
        </form>
    </tfooter>
</table>
</div>
<div class="col-lg-3" id="col2">
    <h2>Editar Resolucion:</h2>
    <form id="editar" method="post">
        <div class="form-group">
            <label for="resolucion">Resolución:</label>
            <input type="text" name="resolucion" id="resolucion" class="form-control" maxlength="20" required/>
        </div>
        <input type="hidden" name="id" id="id"/>
        <input type="submit" value="Guardar" class="btn btn-default"/>
    </form>
</div>
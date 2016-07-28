<H3>Entidades cargadas:</H3>
<div class="col-lg-7">
<table class="table table-striped">
    <thead>
        <tr><td>Razón Social</td><td>Cuit</td><td>Acciones</td></tr>
    </thead>
    <tboby>
    {content}
        <tr>
            <td>{razon_social}</td>
            <td>{cuit}</td>
            <td>
                <button type="button" class="btn btn-default edit" id="{id}" razon_social="{razon_social}" cuit="{cuit}">
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
            <td><input type="text" name="razon_social" class="form-control" maxlength="250" required/></td>
            <td><input type="text" name="cuit" class="form-control" required/></td>
            <td><input type="submit" value="Insertar" class="btn btn-default"/></td>
        </tr>
        </form>
    </tfooter>
</table>
</div>
<div class="col-lg-3" id="col2">
    <h2>Editar Entidad:</h2>
    <form id="editar" method="post">
        <div class="form-group">
            <label for="razon_social">Razon Social:</label>
            <input type="text" name="razon_social" id="razon_social" class="form-control" maxlength="250" required/>
        </div>
        <div class="form-group">
            <label for="cuit">Cuit:</label>
            <input type="text" name="cuit" id="cuit" class="form-control" required/>
        </div>
        <input type="hidden" name="id" id="id"/>
        <input type="submit" value="Guardar" class="btn btn-default"/>
    </form>
</div>
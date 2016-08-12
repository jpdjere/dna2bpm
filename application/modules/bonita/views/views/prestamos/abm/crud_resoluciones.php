<H3>Resoluciones cargadas:</H3>
<div class="col-lg-7">
<table class="table table-striped">
    <thead>
        <tr><td>Resolución</td><td>Montos por categoría</td><td>Acciones</td></tr>
    </thead>
    <tboby>
    {content}
        <tr>
            <td>{resolucion}</td>
            <td>
                <table style="width:100%;">
                {tamano}
                    <tr>
                        <td style="width:100px;">{tamano_parseado}</td><td align="right" style="padding-right:30px;">{monto}</td>
                    </tr>
                {/tamano}
                </table>
            </td>
            <td>
                <button type="button" class="btn btn-default edit" id="{id}" resolucion="{resolucion}" {tamano} tamano[{tamano}]="{monto}" {/tamano}>
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
            <td>
                <input type="number" name="tamano[1]" class="form-control" minlength="1" min="0" placeholder="Micro" required/>
                <input type="number" name="tamano[2]" class="form-control" minlength="1" min="0" placeholder="Pequeña" required/>
                <input type="number" name="tamano[3]" class="form-control" minlength="1" min="0" placeholder="Mediana" required/>
                <input type="number" name="tamano[4]" class="form-control" minlength="1" min="0" placeholder="Grande" required/>
            </td>
            <td><input type="submit" value="Insertar" class="btn btn-default"/></td>
        </tr>
        </form>
    </tfooter>
</table>
</div>
<div class="col-lg-3" id="col2">
    <h2>Editar Resolucion:</h2>
    <form id="editar" method="post">
        <input type="hidden" name="id" id="id"/>
        <div class="form-group">
            <label for="resolucion">Resolución:</label>
            <input type="text" name="resolucion" id="resolucion" class="form-control" maxlength="20" required/>
        </div>
        <div class="form-group">
            <label>Montos por categoría</label>
            <input type="number" name="tamano[1]" id="tamano1" class="form-control" minlength="1" min="0" placeholder="Micro" required/>
            <input type="number" name="tamano[2]" id="tamano2" class="form-control" minlength="1" min="0" placeholder="Pequeña" required/>
            <input type="number" name="tamano[3]" id="tamano3" class="form-control" minlength="1" min="0" placeholder="Mediana" required/>
            <input type="number" name="tamano[4]" id="tamano4" class="form-control" minlength="1" min="0" placeholder="Grande" required/>
        </div>
        <input type="submit" value="Guardar" class="btn btn-default"/>
    </form>
</div>
<H3>CARGA DE ENTIDADES Y MONTOS:</H3></br>
<tr>
    {datos_licitacion}
</tr>
<tr>
    {datos_cargados}
</tr>
<tr>
    <form id="form_entidades" class="cmxform" method="post">
    <table>
        <tr>
            <td>Entidades:</td>
            <td>
                <select id="entidad" name="entidad">
                    <option selected value="">Elija una opción</option>
                    {entidades}
                </select>
            </td>
        </tr>
        <tr>
            <td><label class="error" for="entidad"></label></td>
        </tr>
        <tr>
            <td>Monto Ofrecido (En Millones de $):</td>
            <td><input type="text" id="monto" name="monto"/></td>
        </tr>
        <tr>
            <td><label class="error" for="monto"></label></td>
        </tr>
    </table>
    <div>
        <button id="guardar" class="btn btn-default btn-xs" name="guardar">Guardar</button>
    </div>
    </form>
</tr>

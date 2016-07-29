<h3>Formulario de Consulta:</h3>
<div class="container" style="width:100%;">
    <form method="post" action="{base_url}financiamiento/consulta/search">
        <div class="form-group">
            <label for="query">Opción de búsqueda:</label>
            <select name="param" id="param" class="form-control" required>
                <option value="" disabled selected></option>
                <option value="cuit">Cuit</option>
                <option value="idcase">Caso</option>
                <option value="razon_social">Razón Social</option>
                <option value="mail">Email</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">Valor:</label>
            <input type="text" name="value" id="value" class="form-control" required/>
        </div>
        <input type="submit" value="Consultar"/>
    </form>
</div>
{rango hasta}
<table class="table table-striped table-bordered" data-uniqueid="234059876f7">
    <thead>
        <tr>
            <th data-column="RUBRO">RUBRO
            </th>
            <th data-column="CUITS">CUITS
            </th>
            <th data-column="LOCALES">LOCALES
            </th>
            <th data-column="MONTO_VENTAS">MONTO_VENTAS
            </th>
            <th data-column="OPERACIONES">OPERACIONES
            </th>
        </tr>
    </thead>
    <tbody>
        {xProvincia}
        <tr>
            <td data-type="string">{RUBRO}</td>
            <td data-type="int">{CUITS}</td>
            <td data-type="int">{LOCALES}</td>
            <td data-decimals="2" data-type="real">{MONTO_VENTAS}</td>
            <td data-type="int">{OPERACIONES}</td>
        </tr>
        {/xProvincia}
    </tbody>
</table>

<a href="{base_url}ahora12/xRubro/xls"><icon class="fa fa-download"></icon> xls</a>
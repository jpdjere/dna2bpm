<table class="table table-striped">
    <thead>
        <tr>
            <th></th>
            <th>Nro</th>
            <th>Fecha</th>
            <th>Nombre</th>
            <th>CUIT</th>
            <th>CasoT</th>
        </tr>
    </thead>
    <tbody>
        {empresas}
        <tr>
            <td>
                <a href="{link_open}" target="_blank">
                <i class="ion ion-folder fa-2x fa-adjust"></i>
                </a>
            </td>
            <td>{Nro}</td>
            <td>{fechaent}</td>
            <td>{nombre}</td>
            <td>{cuit}</td>
            <td>{case}</td>
        </tr>
        {/empresas}

    </tbody>
</table>
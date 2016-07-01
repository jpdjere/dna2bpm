<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title">Resultados PN: "{querystring}" <span>({count})</span></h3>
        <div class="box-tools pull-right">
            <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-default btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Nro Proyecto</th>
                    <th>Fecha</th>
                    <th>Empresa</th>
                    <th>CUIT</th>
                    <th>Estado</th>
                    <th>Caso</th>
                </tr>
            </thead>
            <tbody>
                {empresas}
                <tr>
                    <td>
                        {if {url_clone}>0}<a href="{url_clone}" title="Evaluar Caso">
                            <i class="fa fa-play"></i>
                        </a>{/if}
                    </td>
                    <td>
                        <a href="{link_msg}" target="_blank" title="Ver del Proyecto">
                            <i class="ion ion-folder fa-2x fa-adjust"></i>
                        </a>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>{Nro}</td>
                    <td>{fechaent}</td>
                    <td>{nombre}</td>
                    <td>{cuit}</td>
                    <td>{estado}</td>
                    <td>{case}</td>
                </tr>
                {/empresas}

            </tbody>
        </table>
    </div>
</div>
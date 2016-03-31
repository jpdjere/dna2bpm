<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title">Resultados: <span>({count})</span></h3>
        <div class="box-tools pull-right">
            <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-default btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nro Solicitud</th>
                    <th>Provincia</th>
                    <th>Ciudad</th>
                    <th>Intendente</th>
                    <th>Mail Intendente</th>
                    <th>Referente</th>
                    <th>Mail Referente</th>
                    <th>Usuario</th>
                    <th>email</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Caso</th>
                </tr>
            </thead>
            <tbody>
                {proyectos}
                <tr>
                    </td>
                    <td>{Nro}</td>
                    <td>{provincia}</td>
                    <td>{ciudad}</td>
                    <td>{nameInt}</td>
                    <td>{mailInt}</td>
                    <td>{nameRef}</td>
                    <td>{mailRef}</td>
                    <td>{user name} {user lastname}</td>
                    <td>{user email}</td>
                    <td>{fechapresentacion}</td>
                    <td>{estado}</td>
                    <td>{case}</td>
                </tr>
                {/proyectos}

            </tbody>
        </table>
    </div>
</div>
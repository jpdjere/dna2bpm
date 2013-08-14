<div id="info" class="text-left well">
    <h2>
        {type}::{code}
    </h2>

    ESTADO: {pacc_data estado}
    <br/>
    EVALUADOR TECNICO: {pacc_data e_tecnico}
    <br/>
    EVALUADOR ADMINISTRATIVO: {pacc_data e_admin}
    <br/>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Grupo</th>
            <th>Usuario</th>
            <th>Dias</th>
        </tr>
    </thead>
    <tbody>
        {result}
        <tr>
            <td>1</td>
            <td>{date}</td>
            <td>{group}</td>
            <td>{user_data}{name} {lastname}{/user_data}</td>
            <td>{days}</td>
        </tr>
        {/result}

    </tbody>
</table>
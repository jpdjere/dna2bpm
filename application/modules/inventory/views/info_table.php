<span class="btn btn-large btn-success">
    {type}::{code}
</span>
<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Usuario</th>
            <th>Dias</th>
        </tr>
    </thead>
    <tbody>
        {result}
        <tr>
            <td>1</td>
            <td>{date}</td>
            <td>{user_data}{name} {lastname}{/user_data}</td>
            <td>{days}</td>
        </tr>
        {/result}

    </tbody>
</table>
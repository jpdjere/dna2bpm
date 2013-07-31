<!-- HEADER -->
<div id="header_wrap" class="text-center">
    <header class="inner">

        <h1 id="project_title">
            <!--<img  class="img-polaroid" src="{base_url}qr/gen_url/{module_url_encoded}/6/L" width="160" height="160"/>-->  
            {title}
        </h1>
    </header>
</div>
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
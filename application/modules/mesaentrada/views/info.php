<!-- HEADER -->
<div id="header_wrap" class="text-center">
    <header class="inner">

        <h1 id="project_title">
            <!--<img  class="img-polaroid" src="{base_url}qr/gen_url/{module_url_encoded}/6/L" width="160" height="160"/>-->  
            {title}
            <span class="btn btn-large btn-success">
                {type}::{code}
            </span>

        </h1>
    </header>
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
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
<div id="info">
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
</div>
<div class="login img-polaroid">
    <h3>
        Assignar a:
    </h3>
    <form action="{module_url}claim" class="form-inline">
        <div class="input-prepend">
            <span class="add-on"><i class="icon-group"></i> Grupo</span>
            <select name="group" id="group_select">
                {groups}
                <option value="{idgroup}">
                    {name}
                </option>
                {/groups}
            </select>
            <br/>
            <br/>
            <span class="add-on"><i class="icon-user"></i> Usuario</span>
            <select name="user" id="user_select" size="15">
                {users}
                <option value="{idu}">
                    {name} {lastname}
                </option>
                {/users}
            </select>
            <br/>
            <br/>
            <input id="data" type="hidden" value="{data}"/>
            <a id="btn_assign" class="btn btn-success">
                <i class="icon-chevron-right"/>
                Asignar
            </a>
        </div>
    </form>
</div>
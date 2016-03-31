<!-- HEADER -->
<div class="box box-info">
	<div class="box-header">
		<div class="box-title">
			<h3>Assignar {type}:{code} a:</h3>
		</div>
	</div>
		<!-- /.box-header -->
	<div class="box-body ">
	
	<!-- Asignar -->
	
    <form action="{module_url}claim" class="">
    	<!--  Grupos -->
		<div class="form-group ">
	   		<div class="input-group ">
			  <span class="input-group-addon">Grupos</span>
	            <select name="group" id="group_assign" class="form-control">
	                {groups}
	                <option value="{idgroup}">
	                    {name}
	                </option>
	                {/groups}
	            </select>
			</div>
		</div>
		<!--  Usuarios -->
		<div class="form-group ">
		    <div class="input-group ">
			  <span class="input-group-addon">Usuario</span>
	            <select name="user" id="user_assign"  class="form-control">
	                {users}
	                <option value="{idu}">
	                    {name} {lastname}
	                </option>
	                {/users}
	            </select>
			</div>
		</div>

		
		
            <input id="data-assign" type="hidden" value="{data}"/>
            <a id="btn_assign" class="btn btn-primary">
                Asignar
            </a>
    </form>
    <hr>
    <!-- Listado -->
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

	</div>
</div>	
	

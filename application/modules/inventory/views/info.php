<!-- HEADER -->
<div class="box box-info">
<input type="hidden" name="mytype" value="{type}">
<input type="hidden" name="mycode" value="{code}">

	<div class="box-header">
		<i class="fa fa-folder-open"></i>
		<div class="box-title">{title}</div>
	</div>
		<!-- /.box-header -->
	<div class="box-body no-padding">
			<div id="info" >
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
            <td>{user_data name} {user_data lastname}</td>
            <td>{days}</td>
        </tr>
        {/result}

    </tbody>
</table>
	</div>
</div>
	

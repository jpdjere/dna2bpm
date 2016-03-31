<!-- HEADER -->
<div class="box box-info">
	<div class="box-header">
                <i class="fa fa-folder-open"></i>
		<div class="box-title">{title}
		</div>
	</div>
		<!-- /.box-header -->
	<div class="box-body ">
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
            <td>{type}-{code}</td>
            <td >{date}</td>
            <td>{group}</td>
            <td>{user_data}{name} {lastname}{/user_data}</td>
            <td>{days}</td>
        </tr>
        {/result}

    </tbody>
</table>
	</div>
</div>
	
	

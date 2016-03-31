<!-- HEADER -->
<div class="box box-info">
<input type="hidden" name="mytype" value="{type}">
<input type="hidden" name="mycode" value="{code}">  
	<div class="box-header">
		<i class="fa fa-folder-open"></i>
		<div class="box-title">{title}
				<span class="label label-default">{type}</span>
				<span class="label label-default">{code}</span>
		</div>

	</div>
		<!-- /.box-header -->
	<div class="box-body ">
		<div id="info" >
			<ul class="list-unstyled ">
				<li><i class="fa fa-angle-double-right"></i> Estado: <span class='text-primary'>{pacc_data estado}</span></li>
				<li><i class="fa fa-angle-double-right"></i> Evaluador técnico: <span class='text-primary'>{pacc_data e_tecnico}</span></li>
				<li><i class="fa fa-angle-double-right"></i> Evaluador administrativo: <span class='text-primary'>{pacc_data e_admin}</span></li>
			</ul>
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
		            <td>{user_data name} {user_data lastname}</td>
		            <td>{days}</td>
		        </tr>
		        {/result}
		
		    </tbody>
		</table>
	
	
	</div>
</div>
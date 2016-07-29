<table>
	<thead>
		<tr>
			<th>ID Caso</th>
			<th>Nro de Proyecto</th>			
			<th>Nombre</th>
			<th>Apellido</th>
			<th>Género</th>
			<th>Provincia</th>
			<th>Localidad</th>
			<th>Monto Solicitado</th>
			<th>Empresa</th>
			<th>Email</th>
			<th>CUIT</th>			
		</tr>
	</thead>
	<tbody>
		{data}
		<tr>
			<td>{id}</td>
			<td>{numero}</td>
			<td>{nombre}</td>
			<td>{apellido}</td>
			<td>{genero}</td>
			<td>{email}</td>
			<td>{provincia}</td>
			<td>{empresa}</td>
			<td>{email}</td>
			<td>{cuit}</td>			
		</tr>
		{/data}
	</tbody>
</table>
<table>
	<thead>
		<tr>
			<th>Nro de Proyecto</th>			
			<th>Nombre</th>
			<th>Apellido</th>
			<th>Género</th>
			<th>Provincia</th>
			<th>Partido/Departamento</th>
			<th>Localidad</th>
			<th>Monto Solicitado</th>
			<th>Email</th>
			<th>DNI</th>
			<th>Actividad Principal</th>
            <th>Incubadora</th>			
			
		</tr>
	</thead>
	<tbody>
		{data}
		<tr>
			<td>{numero}</td>
			<td>{nombre}</td>
			<td>{apellido}</td>
			<td>{genero}</td>
			<td>{provincia}</td>
	        <td>{partido}</td>			
	        <td>{localidad}</td>
	        <td>{monto_solicitado}</td>
			<td>{email}</td>
			<td>{dni}</td>	
			<td>{actividad_principal}</td>
			<td>{incubadora}</td>
		</tr>
		{/data}
	</tbody>
</table>
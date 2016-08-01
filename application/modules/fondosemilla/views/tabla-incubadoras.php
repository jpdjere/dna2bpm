<table id="tabla-estado" class="table table-bordered table-hover">
    <thead>
          <tr role="row">
              <th>N° Proyecto</th>
              <th>Nombre</th>
              <th>Apellido</th>              
              <th>Provincia</th>
              <th>Partido</th>
              <th>Localidad</th>
              <th>Monto Solicitado</th>
              <th>CUIT</th>
              <th>Actividad Principal</th>
          </tr>
    </thead>
          <tbody>
                {proyectos}
                <tr>
                <td>{10007}</td>
                <td>{9917}</td>
                <td>{pre_aprobados}</td>
                <td>{aprobados}</td>
                <td>{rechazados}</td>
                <td>{finalizados}</td>
                <td>{proyectos_desembolsados}</td>
                <td>{desembolso}</td>
                <td>{desembolso}</td>                
                </tr>
                {/proyectos}
          </tbody>
</table>    

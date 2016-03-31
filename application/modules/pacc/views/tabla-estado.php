<table id="tabla-estado" class="table table-bordered table-hover">
    <thead>
          <tr role="row">
              <th>Provincia</th>
              <th>Proyectos Presentados</th>
              <th>Proyectos Pre-Aprobados</th>
              <th>Proyectos Aprobados</th>
              <th>Proyectos Rechazados</th>
              <th>Proyectos Finalizados</th>
               <th>Proyectos Desembolsados</th>
              <th>Desembolsos Realizados</th>
          </tr>
    </thead>
          <tbody>
               {incubadoras}
                <tr>
                <td>{provincia}</td>
                <td>{presentados}</td>
                <td>{pre_aprobados}</td>
                <td>{aprobados}</td>
                <td>{rechazados}</td>
                <td>{finalizados}</td>
                <td>{proyectos_desembolsados}</td>
                <td>{desembolso}</td>
                </tr>
                {/incubadoras}
          </tbody>
</table>    

<table class="table table-bordered table-hover">
    <thead>
          <tr role="row">
              <th>Provincia</th>
              <th>Proyectos Presentados</th>
              <th>Proyectos Pre-Aprobados</th>
              <th>Proyectos Aprobados</th>
              <th>Proyectos Rechazados</th>
              <th>Proyectos Finalizados</th>
              <th>Desembolsos Realizados</th>
          </tr>
    </thead>
          <tbody>
            {proyectos}
              <tr >
                <td>{prov}</td>
                <td>{presentados}</td>
                <td>{preaprobados}</td>
                <td>{aprobados}</td>
                <td>{rechazados}</td>
                <td>{finalizados}</td>
                <td>{realizados}</td>
              </tr>
            {/proyectos}  
          </tbody>
      </table>
      <div class="row">
        <div class="col-lg-12">
            <div style="margin-top: 10px" class="pull-right"><button class="btn btn-block btn-default">Exportar a Excel</button></div>
      </div>
      </div>
                  
                            

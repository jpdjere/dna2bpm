<div class="table-responsive tour-subsecretaria paso-dos tour-incubar incubar-dos" style="overflow: auto">
<table class="table table-bordered table-hover" >
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
            {proyectos}
              <tr >
                <td>{provincia}</td>
                <td>{presentados cantidad}</td>
                <td>{pre_aprobados cantidad}</td>
                <td>{aprobados cantidad}</td>
                <td>{rechazados cantidad}</td>
                <td>{finalizados cantidad}</td>
                <td>{proyectos_desembolsados}</td>
                <td>{desembolso}</td>
                
              </tr>
            {/proyectos}  
          </tbody>
      </table>
      </div>
      <div class="row">
        <div class="col-lg-12">
            <div style="margin-top: 10px" class="pull-right">
                <a class="btn btn-block btn-default" href="{module_url}incubar/proyectos_empresas_excell" target="_blank">Exportar a Excel</a>
                
            </div>
      </div>
      </div>
                  
                            

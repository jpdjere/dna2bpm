<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs pull-right">
      <li class="active"><a data-toggle="tab" href="#tab1" aria-expanded="true">Proyectos Empresas</a></li>
      <li class=""><a data-toggle="tab" href="#tab2" aria-expanded="false">Proyectos Emprendedores</a></li>
    </ul>
    <div class="tab-content no-padding">
      <div style="position: relative;" id="tab1" class="chart tab-pane active">
          
    <div class="table-responsive no-padding">
        <table class="table table-bordered tour-evaluador evaluador-uno">
            <tbody><tr>
                <th>IP</th>
                <th>Cuit</th>
                <th>Estado del proyecto</th>
                <th>Empresa</th>
                <th>Comentarios</th>
            </tr>
            {empresas}
            <tr>
                <td>{numero}</td>
                <td>{cuit}</td>
                <td>{estado}</td>
                <td>{empresa}</td>
                <td>{comentario}</td>
            </tr>
            {/empresas}
        </tbody>
        </table>
    </div><!-- /.box-body -->

                        
      </div>
      <div style="position: relative;" id="tab2" class="chart tab-pane">
        
     <div class="table-responsive no-padding">
        <table class="table table-bordered">
            <tbody><tr>
                <th>IP</th>
                <th>Cuit</th>
                <th>Estado del proyecto</th>
                <th>Empresa</th>
                <th>Comentarios</th>
            </tr>
            {emprendedores}
            <tr>
                <td>{numero}</td>
                <td>{cuit}</td>
                <td>{estado}</td>
                <td>{empresa}</td>
                <td>{comentario}</td>
            </tr>
            {/emprendedores}
        </tbody>
        </table>
    </div>          
      </div>
    </div>
</div>


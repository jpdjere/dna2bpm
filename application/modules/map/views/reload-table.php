
<div id="table" class="col-md-6 {class}"> 
<span class="hidden json_url">{json_url}</span>     

<table id="devInfo" class="table table-bordered table-hover">
    <thead>
          <tr role="row">
              <th>{provincia}</th>
              <th>Cantidad de Incubadoras</th>
          </tr>
    </thead>
          <tbody>
            {data}
              <tr >
                <td class="pad-seccion">{seccion}</td>
                <td class="pad-cantidad">{cantidad}</td>
              </tr><tr >
           {/data}
          </tbody>
      </table>
      <div style="margin-top: 10px" class="pull-right"><button class="btn btn-block btn-default">Exportar a Excel</button></div>
</div>
</div>
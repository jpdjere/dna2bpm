<div class="row" id="box">
<div class="col-md-6">    
    
<div id="mapdiv" style="width: 100%; height: 500px;"></div>
    
</div>

<div id="table" class="col-lg-6 {class}"> 
<span class="hidden json_url">{json_url}</span>     

<table id="devInfo" class="table table-bordered table-hover" hidden>
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
      <div style="margin-top: 10px" class="pull-right" hidden><button class="btn btn-block btn-default">Exportar a Excel</button></div>
</div>
</div>
<div class="tour-subsecretaria paso-uno tour-incubar incubar-uno row" id="box">
<div class="col-lg-6">    
    
<div id="mapdiv" style="width: 100%; height: 500px; min-height: 555px"></div>
    
</div>

<div id="table" class="col-lg-6"> 
  
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
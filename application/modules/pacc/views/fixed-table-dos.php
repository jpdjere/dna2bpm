<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs pull-right" style="cursor: move;">
      <li class="active"><a aria-expanded="true" href="#tabla1" data-toggle="tab">Tab1</a></li>
      <li class=""><a aria-expanded="false" href="#tabla2" data-toggle="tab">Tab2</a></li>
      <li class=""><a aria-expanded="false" href="#tabla3" data-toggle="tab">Tab3</a></li>
      <li class=""><a aria-expanded="false" href="#tabla4" data-toggle="tab">Tab4</a></li>
      <li class="pull-left header"><i class="fa fa-inbox"></i> {title}</li>
    </ul>
    <div class="tab-content">
      <div class="chart tab-pane active table-responsive" id="tabla1" style="position: relative;">
         <table class="table table-bordered">
   <thead>
      <tr>
         <th colspan="12" scope="col" class="info-table-center">Productos (Bien, servicio, proyecto o norma)</th>
      </tr>
      <tr>
         <th scope="col" colspan="6"  class="info-table-center">Indicador Producto</th>
         <th scope="col" rowspan="3" class="info-table-center pad-thead-dos">Costo Unitario</th>
         <th scope="col" rowspan="3" class="info-table-center pad-thead-dos">Costo Unitario U$D</th>
         <th scope="col" rowspan="3" class="info-table-center pad-thead-dos">Inciso ONP</th>
         <th colspan="3" scope="col" class="info-table-center">Fuente</th>
         
     </tr>
  <tr>
         <th scope="col" rowspan="2" class="info-table-center pad-thead">Unidad Medida</th>
         <th class="info-table-center">TI</th>
         <th class="info-table-center">TII</th>
         <th class="info-table-center">TIII</th>
         <th class="info-table-center">TIV</th>
         <th class="info-table-center">Total</th>
         <th scope="col" rowspan="2" class="info-table-center pad-thead">22</th>
         <th scope="col" rowspan="2" class="info-table-center pad-thead">11</th>
         <th scope="col" rowspan="2" class="info-table-center pad-thead">PYME</th>
      </tr>
        <tr>
       
         <th class="info-table-center">Estim</th>
         <th class="info-table-center">Estim</th>
         <th class="info-table-center">Estim</th>
         <th class="info-table-center">Estim</th>
         <th class="info-table-center">Estim</th>
      </tr>
   </thead>
   <tbody id="t1">
      {reporte}
      <tr class="size-table-font">
         <td>{IP_UNIDAD}</td>
         <td>{IP_TI}</td>
         <td>{IP_TII}</td>
         <td>{IP_TIII}</td>
         <td>{IP_TIV}</td>
         <td>{IP_TOTAL}</td>
         <td>{COSTO_UNI}</td>
         <td>13</td>
         <td>{Inciso_ONP}</td>
         <td>{FUENTE_22}</td>
         <td>{FUENTE_11}</td>
         <td>{FUENTE_PYME}</td>
       
      </tr>
     {/reporte}
   </tbody>
</table>
      </div>
      <div class="chart tab-pane table-responsive" id="tabla2" style="position: relative;">
                   <table class="table table-bordered ">
   <thead>
      <tr>
         <th colspan="16" scope="col" class="info-table-center">Indicador Presupuestario</th>
      </tr>
      <tr>
         <th scope="col" colspan="3"  class="info-table-center">T I ($)</th>
         <th scope="col" colspan="3" class="info-table-center">T II ($)</th>
         <th scope="col" colspan="3" class="info-table-center">T III ($)</th>
         <th colspan="3" scope="col" class="info-table-center">T IV ($)</th>
         <th scope="col" colspan="3" class="info-table-center">Total por Fuente</th>
         <th rowspan="3" scope="col" class="info-table-center pad-thead-dos">Total ($)</th>
         
     </tr>

        <tr>
       
         <th class="info-table-center">BID</th>
         <th class="info-table-center">Nación</th>
         <th class="info-table-center">Aporte PYME</th>
         <th class="info-table-center">BID</th>
         <th class="info-table-center">Nación</th>
         <th class="info-table-center">Aporte PYME</th>
         <th class="info-table-center">BID</th>
         <th class="info-table-center">Nación</th>
         <th class="info-table-center">Aporte PYME</th>
         <th class="info-table-center">BID</th>
         <th class="info-table-center">Nación</th>
         <th class="info-table-center">Aporte PYME</th>
         <th rowspan="2" scope="col"  class="info-table-center pad-thead">BID</th>
         <th rowspan="2" scope="col"  class="info-table-center pad-thead">Nación</th>
         <th rowspan="2" scope="col"  class="info-table-center pad-thead">Aporte PYME</th>
               
      </tr>
      <tr>
          <th class="info-table-center">Est</th>
         <th class="info-table-center">Est</th>
         <th class="info-table-center">Est</th>
           <th class="info-table-center">Est</th>
         <th class="info-table-center">Est</th>
         <th class="info-table-center">Est</th>
           <th class="info-table-center">Est</th>
         <th class="info-table-center">Est</th>
         <th class="info-table-center">Est</th>
           <th class="info-table-center">Est</th>
         <th class="info-table-center">Est</th>
         <th class="info-table-center">Est</th>
      </tr>
   </thead>
   <tbody class="size-table-font" id="t2">
      {reporte}
      <tr>
         <td>{PESO_TI_BID}</td>
         <td>{PESO_TI_BNA}</td>
         <td>{PESO_TI_PYME}</td>
         <td>{PESO_TII_BID}</td>
         <td>{PESO_TII_BNA}</td>
         <td>{PESO_TII_PYME}</td>
         <td>{PESO_TIII_BID}</td>
         <td>{PESO_TIII_BNA}</td>
         <td>{PESO_TIII_PYME}</td>
         <td>{PESO_TIV_BID}</td>
         <td>{PESO_TIV_BNA}</td>
         <td>{PESO_TIV_PYME}</td>
         <td>{PESO_TOTFUE_BID}</td>
         <td>{PESO_TOTFUE_BNA}</td>
         <td>{PESO_TOTFUE_PYME}</td> 
         <td>{PESO_TOTAL}</td>
      </tr>
     {/reporte}
   </tbody>
</table>
      </div>
            <div class="chart tab-pane table-responsive" id="tabla3" style="position: relative;">
                   <table class="table table-bordered">
   <thead>
      <tr>
         <th colspan="16" scope="col" class="info-table-center">Indicador Presupuestario</th>
      </tr>
      <tr>
         <th scope="col" colspan="3"  class="info-table-center">T I ($)</th>
         <th scope="col" colspan="3" class="info-table-center">T II ($)</th>
         <th scope="col" colspan="3" class="info-table-center">T III ($)</th>
         <th colspan="3" scope="col" class="info-table-center">T IV ($)</th>
         <th scope="col" colspan="3" class="info-table-center">Total por Fuente</th>
         <th rowspan="3" scope="col" class="info-table-center pad-thead-dos">Total ($)</th>
     </tr>

        <tr>
       
         <th class="info-table-center">BID</th>
         <th class="info-table-center">Nación</th>
         <th class="info-table-center">Aporte PYME</th>
         <th class="info-table-center">BID</th>
         <th class="info-table-center">Nación</th>
         <th class="info-table-center">Aporte PYME</th>
         <th rowspan="2" scope="col" class="info-table-center pad-thead">BID</th>
         <th rowspan="2" scope="col" class="info-table-center pad-thead">Nación</th>
         <th rowspan="2" scope="col" class="info-table-center pad-thead">Aporte PYME</th>
         <th rowspan="2" scope="col" class="info-table-center pad-thead">BID</th>
         <th rowspan="2" scope="col" class="info-table-center pad-thead">Nación</th>
         <th rowspan="2" scope="col" class="info-table-center pad-thead">Aporte PYME</th>
         <th rowspan="2" scope="col"  class="info-table-center pad-thead">BID</th>
         <th rowspan="2" scope="col"  class="info-table-center pad-thead">Nación</th>
         <th rowspan="2" scope="col"  class="info-table-center pad-thead">Aporte PYME</th>
               
      </tr>
      <tr>
         <th class="info-table-center">Est</th>
         <th class="info-table-center">Est</th>
         <th class="info-table-center">Est</th>
         <th class="info-table-center">Est</th>
         <th class="info-table-center">Est</th>
         <th class="info-table-center">Est</th>
   </thead>
   <tbody class="size-table-font" id="t2">
      {reporte}
      <tr>
         <td>{USD_TI_BID}</td>
         <td>{USD_TI_BNA}</td>
         <td>{USD_TI_PYME}</td>
         <td>{USD_TII_BID}</td>
         <td>{USD_TII_BNA}</td>
         <td>{USD_TII_PYME}</td>
         <td>{USD_TIII_BID}</td>
         <td>{USD_TIII_BNA}</td>
         <td>{USD_TIII_PYME}</td>
         <td>{USD_TIV_BID}</td>
         <td>{USD_TIV_BNA}</td>
         <td>{USD_TIV_PYME}</td>
         <td>{USD_TOTFUE_BID}</td>
         <td>{USD_TOTFUE_BNA}</td>
         <td>{USD_TOTFUE_PYME}</td> 
         <td>{USD_TOTAL}</td>
      </tr>
     {/reporte}
   </tbody>
</table>
      </div>
      <div class="chart tab-pane table-responsive" id="tabla4" style="position: relative;">
                   <table class="table table-bordered ">
   <thead>
      <tr>
         <th colspan="21" scope="col" class="info-table-center">Indicador Presupuestario</th>
      </tr>
      <tr>
         <th scope="col" colspan="5"  class="info-table-center">Indicador Producto</th>
         <th scope="col" colspan="3"  class="info-table-center">T I ($)</th>
         <th scope="col" colspan="3" class="info-table-center">T II ($)</th>
         <th scope="col" colspan="3" class="info-table-center">T III ($)</th>
         <th colspan="3" scope="col" class="info-table-center">T IV ($)</th>
         <th scope="col" colspan="3" class="info-table-center">Total por Fuente</th>
         <th rowspan="3" scope="col" class="info-table-center pad-thead-dos">Total ($)</th>
         
     </tr>

        <tr>
         <th class="info-table-center">TI</th>
         <th class="info-table-center">TII</th>
         <th class="info-table-center">TIII</th>
         <th class="info-table-center">TIV</th>
         <th class="info-table-center">Total</th>

         <th class="info-table-center">BID</th>
         <th class="info-table-center">Nación</th>
         <th class="info-table-center">Aporte PYME</th>
         <th class="info-table-center">BID</th>
         <th class="info-table-center">Nación</th>
         <th class="info-table-center">Aporte PYME</th>
         <th class="info-table-center">BID</th>
         <th class="info-table-center">Nación</th>
         <th class="info-table-center">Aporte PYME</th>
         <th class="info-table-center">BID</th>
         <th class="info-table-center">Nación</th>
         <th class="info-table-center">Aporte PYME</th>
         <th rowspan="2" scope="col"  class="info-table-center pad-thead">BID</th>
         <th rowspan="2" scope="col"  class="info-table-center pad-thead">Nación</th>
         <th rowspan="2" scope="col"  class="info-table-center pad-thead">Aporte PYME</th>
               
      </tr>
      <tr>
        <th class="info-table-center">Real</th>
         <th class="info-table-center">Real</th>
         <th class="info-table-center">Real</th>
           <th class="info-table-center">Real</th>
           <th class="info-table-center">Real</th>

          <th class="info-table-center">Real</th>
         <th class="info-table-center">Real</th>
         <th class="info-table-center">Real</th>
           <th class="info-table-center">Real</th>
         <th class="info-table-center">Real</th>
         <th class="info-table-center">Real</th>
           <th class="info-table-center">Real</th>
         <th class="info-table-center">Real</th>
         <th class="info-table-center">Real</th>
           <th class="info-table-center">Real</th>
         <th class="info-table-center">Real</th>
         <th class="info-table-center">Real</th>
      </tr>
   </thead>
   <tbody class="size-table-font" id="t2">
      {reporte}
      <tr>
          <td>{IP_TI_REAL}</td>
         <td>{IP_TII_REAL}</td>
         <td>{IP_TIII_REAL}</td>

         <td>{IP_TIV_REAL}</td>
         <td>{IP_TOTAL_REAL}</td>



         <td>{PESO_TI_BID_REAL}</td>
         <td>{PESO_TI_BNA_REAL}</td>
         <td>{PESO_TI_PYME_REAL}</td>

         <td>{PESO_TII_BID_REAL}</td>
         <td>{PESO_TII_BNA_REAL}</td>
         <td>{PESO_TII_PYME_REAL}</td>

         <td>{PESO_TIII_BNA_REAL}</td>
         <td>{PESO_TIII_BID_REAL}</td>
         <td>{PESO_TIII_PYME_REAL}</td>

         <td>{PESO_TIV_BNA_REAL}</td>
         <td>{PESO_TIV_BID_REAL}</td>
         <td>{PESO_TIV_PYME_REAL}</td>

         <td>{PESO_TOTFUE_BNA_REAL}</td>
         <td>{PESO_TOTFUE_BID_REAL}</td>
         <td>{PESO_TOTFUE_PYME_REAL}</td> 
         <td>{PESO_TOTAL_REAL}</td>
      </tr>
     {/reporte}
   </tbody>
</table>
      </div>
    </div>
</div>
